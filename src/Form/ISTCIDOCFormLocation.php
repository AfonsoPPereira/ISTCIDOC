<?php
namespace ISTCIDOC\Form;

use ISTCIDOC\Form\Element\ISTCIDOCResourceClassSelect;
use Omeka\Form\Element\PropertySelect;
use Omeka\Api\Adapter\PropertyAdapter;

use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\EventManager\Event;

class ISTCIDOCFormLocation extends Form
{
    use EventManagerAwareTrait;

    public function init()
    {
        $this->add([
            'name' => 'o:uri',
            'type' => 'text',
            'attributes' => array(
                'readonly' => TRUE,
                'id' => 'uri',
            ),
            'options' => [
                'label' => 'Location URI', // @translate
                'info' => 'Location identifier.', // @translate
            ],
        ]);

        $this->add([
            'name' => 'o:local',
            'type' => 'text',
            'attributes' => array(
                'readonly' => TRUE,
                'id' => 'local',
            ),
            'options' => [
                'label' => 'Location Title', // @translate
                'info' => 'Title of the location.', // @translate
            ],
        ]);

        $this->add([
            'name' => 'o:position',
            'type' => 'text',
            'attributes' => array(
                'id' => 'position',
                'required' => true,
            ),
            'options' => [
                'label' => 'Location Position', // @translate
                'info' => 'More specific position.', // @translate
            ],
        ]);
    }
}
