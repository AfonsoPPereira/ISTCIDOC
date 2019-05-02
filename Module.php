<?php
namespace ISTCIDOC;

use Omeka\Module\AbstractModule;
use Zend\ServiceManager\ServiceLocatorInterface;
use Omeka\Permissions\Assertion\OwnsEntityAssertion;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;
use Doctrine\ORM\EntityManager;
use Omeka\Entity\ResourceClass;
use Omeka\Entity\Property;
use Omeka\Entity\Vocabulary;
use Zend\View\Renderer\PhpRenderer;
use Zend\Mvc\Controller\AbstractController;

use ISTCIDOC\Form\ConfigForm;


class Module extends AbstractModule
{
	/**
     * @var EntityManager
     */
    protected $entityManager;
	
	/**
     * The created ontology.
     *
     * @var Vocabulary
     */
    protected $ontology;
	
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event)
    {
        parent::onBootstrap($event);

        $acl = $this->getServiceLocator()->get('Omeka\Acl');
        $acl->allow(
            null,
            'ISTCIDOC\Controller\Index',
			['browse', 'show-details']
        );
        $acl->allow(
            null,
            'ISTCIDOC\Api\Adapter\ISTCIDOCItemAdapter',
            ['search', 'read']
        );
        $acl->allow(
            null,
            'ISTCIDOC\Entity\Location',
            ['read']
        );
        $acl->allow(
            'editor',
            'ISTCIDOC\Controller\Index',
            ['add', 'edit', 'delete']
        );
        $acl->allow(
            'editor',
            'ISTCIDOC\Api\Adapter\ISTCIDOCItemAdapter',
            ['create', 'update', 'delete']
        );
        $acl->allow(
            'editor',
            'ISTCIDOC\Entity\Location',
            'create'
        );
         $acl->allow(
            null,
            'ISTCIDOC\Api\Adapter\LocationAdapter',
            ['search', 'read']
        );
        $acl->allow(
            'editor',
            'ISTCIDOC\Entity\Location',
            ['update', 'delete'],
            new OwnsEntityAssertion
        );
    }
	
	/**
     * Save elements.
     *
     * The entity manager is used, because the api doesn't allow to create
     * custom elements. The method doesn't flush created elements.
     *
     * @param array $elements
     * @param string $type "resouce_classes" or "properties"
     * @return bool|null
     */
    protected function saveElements(array $elements, $type, $serviceLocator)
    {
        if (empty($elements)) {
            return;
        }

        $types = [
            'resource_classes' => 'Resource classes', // @translate
            'properties' => 'Properties', // @translate
        ];
        if (!isset($types[$type])) {
            return;
        }

        $entityClasses = [
            'resource_classes' => ResourceClass::class,
            'properties' => Property::class,
        ];

        //$entityManager = $this->entityManager;
        $this->entityManager = $serviceLocator->get('Omeka\EntityManager');
        $entityClass = $entityClasses[$type];

        $owner = null;

        foreach ($elements as $element) {
            //if (is_string($element['o:vocabulary'])) {
                $element['o:vocabulary'] = $this->ontology;
            //}

            $entity = new $entityClass();
            $entity->setOwner($owner);
            $entity->setVocabulary($element['o:vocabulary']);
            $entity->setLocalName($element['o:local_name']);
            $entity->setLabel($element['o:label']);
            $entity->setComment($element['o:comment']);
            $this->entityManager->persist($entity);
        }

        return true;
    }
	
	/**
     * Save resource classes.
     *
     * @param array $resourceClasses
     * @return bool|null
     */
    protected function saveResourceClasses(array $resourceClasses, $serviceLocator)
    {
        return $this->saveElements($resourceClasses, 'resource_classes', $serviceLocator, $this->ontology);
    }

    /**
     * Save properties.
     *
     * @param array $properties
     * @return bool|null
     */
    protected function saveProperties(array $properties, $serviceLocator)
    {
        return $this->saveElements($properties, 'properties', $serviceLocator, $this->ontology);
    }
	
	
	public function install(ServiceLocatorInterface $serviceLocator)
    {
        $api = $serviceLocator->get('Omeka\ApiManager');
		$search = $api->search('vocabularies',
			['prefix' => 'istcidoc'],
			[],
			['responseContent' => 'resource'])->getContent();
			
		if (!isset($search[0])){
			$ontology = Array
			(
				"o:namespace_uri" => __DIR__,
				"o:prefix" => "istcidoc",
				"o:label" => "IST CIDOC",
				"o:vocabulary" => "istcidoc",
				"o:comment" => "",
				"o:class" => Array
					(
					),
				"o:property" => Array
					(
					)
			);
			
			$result = $api->create('vocabularies', $ontology);
			
			$vocabulary = $api->read('vocabularies',
				['prefix' => 'istcidoc'],
				[],
				['responseContent' => 'resource']
			)->getContent();
			
			$this->ontology = $vocabulary;

			require 'files/requires.php';
			include 'files/classes_array.php';
			include 'files/properties_array.php';
			
			$resultResourceClasses = $this->saveResourceClasses($class_array, $serviceLocator);
			$resultProperties = $this->saveProperties($properties_array, $serviceLocator);

			$this->entityManager->flush();
		}
		
        $conn = $serviceLocator->get('Omeka\Connection');
        $conn->exec('CREATE TABLE IF NOT EXISTS location (id INT AUTO_INCREMENT NOT NULL, uri BIGINT DEFAULT NULL, `local` VARCHAR(190) NOT NULL, `position` VARCHAR(190) NOT NULL, UNIQUE INDEX UNIQ_8533D2A5EA750E8 (uri, local, position), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;');
    }
	
	public function uninstall(ServiceLocatorInterface $serviceLocator)
    {
		$settings = $this->getServiceLocator()->get('Omeka\Settings');
		
		if ($settings->get('istcidoc_delete_vocabulary') == 'yes'){
			$api = $serviceLocator->get('Omeka\ApiManager');
			$vocabulary = $api->search('vocabularies',
					['prefix' => 'istcidoc'],
					[],
					['responseContent' => 'resource']
				)->getContent();
			
			if (!$vocabulary) return false;	
			
			$vocabID = $vocabulary[0]->id();
			
			$api->delete('vocabularies', $vocabID);
		}
		
		if ($settings->get('istcidoc_delete_locations') == 'yes'){
			$conn = $serviceLocator->get('Omeka\Connection');
			$conn->exec('DROP TABLE IF EXISTS location');
		}
	}

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        $sharedEventManager->attach(
            'Omeka\DataType\Manager',
            'service.registered_names',
            [$this, 'addLocationServices']
        );
    }

    public function addLocationServices(Event $event)
    {
        $locations = $this->getServiceLocator()->get('Omeka\ApiManager')
            ->search('locations')->getContent();
        if (!$locations) {
            return;
        }
        $names = $event->getParam('registered_names');
        foreach ($locations as $location) {
            $names[] = 'location:' . $location->id();
        }
        $event->setParam('registered_names', $names);
    }
	
	public function getConfigForm(PhpRenderer $renderer)
    {
        $settings = $this->getServiceLocator()->get('Omeka\Settings');
        $form = new ConfigForm;
        $form->init();
        $form->setData([
            'delete_vocabulary' => $settings->get('istcidoc_delete_vocabulary', 'no'),
            'delete_locations' => $settings->get('istcidoc_delete_locations', 'no'),
        ]);
		
        return $renderer->formCollection($form, false);
    }
	
	public function handleConfigForm(AbstractController $controller)
    {
        $settings = $this->getServiceLocator()->get('Omeka\Settings');
        $form = new ConfigForm;
        $form->init();
        $form->setData($controller->params()->fromPost());
        if (!$form->isValid()) {
            $controller->messenger()->addErrors($form->getMessages());
            return false;
        }
        $formData = $form->getData();
        $settings->set('istcidoc_delete_vocabulary', $formData['delete_vocabulary']);
        $settings->set('istcidoc_delete_locations', $formData['delete_locations']);
		
        return true;
    }
}
