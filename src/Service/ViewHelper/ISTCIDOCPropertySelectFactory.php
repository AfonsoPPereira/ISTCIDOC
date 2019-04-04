<?php
namespace ISTCIDOC\Service\ViewHelper;

use ISTCIDOC\View\Helper\ISTCIDOCPropertySelect;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ISTCIDOCPropertySelectFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        return new ISTCIDOCPropertySelect($services->get('FormElementManager'));
    }
}
