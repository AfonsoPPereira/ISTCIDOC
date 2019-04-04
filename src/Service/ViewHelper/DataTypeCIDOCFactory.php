<?php

namespace ISTCIDOC\Service\ViewHelper;

use ISTCIDOC\View\Helper\DataTypeCIDOC;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * Service factory for the dataType view helper.
 */
class DataTypeCIDOCFactory implements FactoryInterface
{
    /**
     * Create and return the dataType view helper
     *
     * @return DataType
     */
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        return new DataTypeCIDOC($services->get('ISTCIDOC\DataTypeManager'));
    }
}
