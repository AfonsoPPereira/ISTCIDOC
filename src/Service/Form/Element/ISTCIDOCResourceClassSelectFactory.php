<?php
namespace ISTCIDOC\Service\Form\Element;

use ISTCIDOC\Form\Element\ISTCIDOCResourceClassSelect;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ISTCIDOCResourceClassSelectFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        $element = new ISTCIDOCResourceClassSelect;
        $element->setApiManager($services->get('Omeka\ApiManager'));
        $element->setEventManager($services->get('EventManager'));
        return $element;
    }
}
