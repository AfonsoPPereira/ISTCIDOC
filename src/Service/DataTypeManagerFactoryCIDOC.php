<?php
namespace ISTCIDOC\Service;

use Omeka\DataType\Manager;
use Omeka\Service\DataTypeManagerFactory;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class DataTypeManagerFactoryCIDOC extends DataTypeManagerFactory
{
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        $config = $serviceLocator->get('Config');
        if (!isset($config['data_types'])) {
            throw new Exception\ConfigException('Missing data type configuration');
        }
        return new Manager($serviceLocator, $config['data_types']);
    }
}
