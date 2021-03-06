<?php
namespace ISTCIDOC\View\Helper;

use ISTCIDOC\Form\Element\ISTCIDOCPropertySelect as Select;
use Zend\Form\Factory;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * View helper for rendering a select menu containing all properties.
 */
class ISTCIDOCPropertySelect extends AbstractHelper
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $formElementManager;

    /**
     * Construct the helper.
     *
     * @param ServiceLocatorInterface $formElementManager
     */
    public function __construct(ServiceLocatorInterface $formElementManager)
    {
        $this->formElementManager = $formElementManager;
    }

    /**
     * Render a select menu containing all properties.
     *
     * @param array $spec
     * @return string
     */
    public function __invoke(array $spec = [])
    {
        $spec['type'] = Select::class;
        if (!isset($spec['options']['empty_option'])) {
            $spec['options']['empty_option'] = 'Select property…'; // @translate
        }
        $factory = new Factory($this->formElementManager);
        $element = $factory->createElement($spec);
        return $this->getView()->formSelect($element);
    }
}
