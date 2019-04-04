<?php
namespace ISTCIDOC\Controller;

use ISTCIDOC\Form\ISTCIDOCForm;
use ISTCIDOC\Form\ISTCIDOCFormLocation;

use Doctrine\ORM\EntityManager;
use Omeka\Entity\ResourceClass;
use Omeka\Entity\Property;
use Omeka\Entity\EntityInterface;
use Zend\Mvc\Controller\AbstractActionController;
//use Omeka\Admin\Controller\ItemController;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorInterface;
use Omeka\Form\ConfirmForm;
use Omeka\Stdlib\Message;


class IndexController extends AbstractActionController
{
    protected function createItem(array $item)
    {
        if (empty($item)) {
            return;
        }

        $data = array();
        $flag = 0;

        foreach ($item as $key => $value){
            if ($key == 'o:resource_class'){
                $value = trim($value);
                if ($value == '')
                    continue;

                $data["o:resource_class"]["o:id"] = $value;
                $flag = 1;
            }else{
                $trim_key = trim(str_replace(' ', '_', $key));

                $search = $this->api()->search('properties', array('term' => $trim_key))->getContent();

                if (!isset($search[0]))
                    continue;

                $property_id = $search[0]->id();

                if ($item[$key]['type'][0] == 'literal'){
                    $val = trim($value['value'][1]);
                    if ($val == '')
                        continue;

                    $data[$key][0]["type"] = "literal";
                    $data[$key][0]["property_id"] = $property_id;
                    $data[$key][0]["@value"] = $val;
                    $flag = 1;
                }elseif ($item[$key]['type'][0] == 'resource' or $item[$key]['type'][0] == 'resource_cidoc'){
                    if (trim($value['value'][0]) == '')
                        continue;

                    foreach ($item[$key]['value'] as $key2 => $value2) {
                        $locationSel = $this->api()->search('locations', array('id' => trim($value2)))->getContent();
                        $data[$key][$key2]["type"] = "literal";
                        $data[$key][$key2]["property_id"] = $property_id;
                        $data[$key][$key2]["@value"] = trim($value2);
                        $data[$key][$key2]["@language"] = $locationSel[0]->local();
                    }

                    $flag = 1;
                }elseif ($item[$key]['type'][0] == 'uri'){
                    $val = trim($value['value'][0]);
                    $val2 = trim($value['value'][1]);
                    if ($val == '' && $val2 == '')
                        continue;

                    $data[$key][0]["type"] = "uri";
                    $data[$key][0]["property_id"] = $property_id;
                    $data[$key][0]["@id"] = $val;
                    $data[$key][0]["o:label"] = $val2;
                    $flag = 1;
                }elseif ($item[$key]['type'][0] == 'location'){
                    exit();
                    $val = trim($value['value'][0]);
                    $val2 = trim($value['value'][1]);
                    if ($val == '' && $val2 == '')
                        continue;

                    $search = $this->api()->search('resource_classes', array('term' => 'istcidoc:Location'))->getContent();

                    if (!isset($search[0]))
                        continue;

                    $class_id = $search[0]->id();

                    $data["o:resource_class"]["o:id"] = $class_id;
                    $flag = 1;

                    $search = $this->api()->search('properties', array('term' => 'istcidoc:location_uri'))->getContent();

                    if (!isset($search[0]))
                        continue;

                    $property_id = $search[0]->id();
                    $key = 'istcidoc:location_uri';

                    $data[$key][0]["type"] = "location";
                    $data[$key][0]["property_id"] = $property_id;
                    $data[$key][0]["@value"] = $val;

                    $search = $this->api()->search('properties', array('term' => 'istcidoc:location_position'))->getContent();

                    if (!isset($search[0]))
                        continue;

                    $property_id = $search[0]->id();
                    $key = 'istcidoc:location_position';

                    $data[$key][0]["type"] = "location";
                    $data[$key][0]["property_id"] = $property_id;
                    $data[$key][0]["@value"] = $val2;
                }
            }
        }

        if (!$flag){
            $this->messenger()->addError(new Message(
                'At least one item field and/or class needs to be filled.' // @translate
            ));
            return false;
        }

        $result = $this->api()->create('istcidoc_items', $data);
        //$result = true;
        if (!$result){
            $this->messenger()->addError(new Message(
                'An issue occurred when creating an item.' // @translate
            ));
            return false;
        }
        //print_r($data);
        //exit();
        return true;
    }

    public function sidebarSelectAction()
    {
        $this->setBrowseDefaults('created');
        $response = $this->api()->search('locations', $this->params()->fromQuery());
        $this->paginator($response->getTotalResults(), $this->params()->fromQuery('page'));

        $view = new ViewModel;
        $view->setVariable('items', $response->getContent());
        $view->setVariable('search', $this->params()->fromQuery('search'));
        $view->setVariable('resourceClassId', $this->params()->fromQuery('resource_class_id'));
        $view->setVariable('itemSetId', $this->params()->fromQuery('item_set_id'));
        $view->setVariable('id', $this->params()->fromQuery('id'));
        $view->setVariable('showDetails', true);
        $view->setTerminal(true);
        return $view;
    }

    public function showAction()
    {
        $response = $this->api()->read('istcidoc_items', $this->params('id'));

        $view = new ViewModel;
        $item = $response->getContent();
        $view->setVariable('item', $item);
        $view->setVariable('resource', $item);
        return $view;
    }

    public function indexAction()
    {
        $view = new ViewModel;
        $request = $this->getRequest();

        $form = $this->getForm(ISTCIDOCForm::class);

        $form->init();
        $view->setVariable('form', $form);

        if (!$request->isPost()) {
            return $view;
        }
        
        $params = $this->getRequest()->getPost();
        $form->setData($params);
        if (!$form->isValid()) {
            $this->messenger()->addErrors($form->getMessages());
            return $view;
        }

        $params = $form->getData();
        $action = $this->params()->fromPost('submit', 'submit');

        $valid = [];
        $valid['item'] = $params['ist_cidoc_fieldset'];
    
        $result = $this->createItem($valid['item']);
        if ($result === false) {
            return $view;
        }
        
        $this->messenger()->addSuccess(new Message(
            'Item successfully created.'
        ));

        $form->init();

        return $view;
    }

    public function browseAction()
    {
        $this->setBrowseDefaults('created');
        $response = $this->api()->search('istcidoc_items', $this->params()->fromQuery());
        $this->paginator($response->getTotalResults(), $this->params()->fromQuery('page'));

        $formDeleteSelected = $this->getForm(ConfirmForm::class);
        //print_r($this->url()->fromRoute(null, ['action' => 'batch-asd'], true)); exit();
        $formDeleteSelected->setAttribute('action', '../item/batch-delete');
        $formDeleteSelected->setButtonLabel('Confirm Delete'); // @translate
        $formDeleteSelected->setAttribute('id', 'confirm-delete-selected');

        $formDeleteAll = $this->getForm(ConfirmForm::class);
        $formDeleteAll->setAttribute('action', '../item/batch-delete-all');
        $formDeleteAll->setButtonLabel('Confirm Delete'); // @translate
        $formDeleteAll->setAttribute('id', 'confirm-delete-all');
        $formDeleteAll->get('submit')->setAttribute('disabled', true);

        $view = new ViewModel;
        $items = $response->getContent();
        $view->setVariable('items', $items);
        $view->setVariable('resources', $items);
        $view->setVariable('formDeleteSelected', $formDeleteSelected);
        $view->setVariable('formDeleteAll', $formDeleteAll);
        return $view;
    }
}
