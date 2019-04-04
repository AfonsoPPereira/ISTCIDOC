<?php
namespace ISTCIDOC\Service\Controller;

use ISTCIDOC\Controller\LocationController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class LocationControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedNamed, array $options = null)
    {
        return new LocationController(
            $services->get('Omeka\Media\Ingester\Manager')
        );
    }
}
