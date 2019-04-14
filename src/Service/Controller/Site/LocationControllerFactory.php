<?php
namespace ISTCIDOC\Service\Site\Controller;

use ISTCIDOC\Controller\Site\LocationController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class LocationControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedNamed, array $options = null)
    {
        $entityManager = $services->get('Omeka\EntityManager');
        return new LocationController(
            $entityManager
        );
    }
}
