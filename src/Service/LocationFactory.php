<?php
namespace ISTCIDOC\Service;

use ISTCIDOC\DataType\Location;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class LocationFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $services, $requestedName)
    {
        return (bool) preg_match('/^location:\d+$/', $requestedName);
    }

    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        // Derive the custom vocab ID, fetch the representation, and pass it to
        // the data type.
        $id = (int) substr($requestedName, strrpos($requestedName, ':') + 1);
        $location = $services->get('Omeka\ApiManager')
            ->read('locations', $id)->getContent();
        return new Location($location);
    }
}
