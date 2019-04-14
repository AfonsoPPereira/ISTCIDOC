<?php
namespace ISTCIDOC\Service\Site\Controller;

use ISTCIDOC\Controller\Site\ItemController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ItemControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedNamed, array $options = null)
    {
        $entityManager = $services->get('Omeka\EntityManager');
        return new ItemController(
            $entityManager
        );
    }
}
