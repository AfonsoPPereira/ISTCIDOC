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

class ISTCIDOCForm extends Form
{
    use EventManagerAwareTrait;

    public function init()
    {
        //$this->setAttribute('class', 'resource-form');  
        $this->add([
            'name' => 'ist_cidoc_fieldset',
            'type' => Fieldset::class,
            'options' => [
                'label' => 'IST CIDOC', // @translate
            ],
            'attributes' => [
                'id' => 'resource-values',
                'class' => 'section active',
            ],
        ]);

        $istCIDOCFieldset = $this->get('ist_cidoc_fieldset');

        $istCIDOCFieldset->add([
            'name' => 'o:resource_class',
            'type' => ISTCIDOCResourceClassSelect::class,
            'attributes' => [
                'id' => 'resource-class-select',
                'class' => 'chosen-select',
                'data-placeholder' => 'Select a class', // @translate
            ],
            'options' => [
                'label' => 'Class', // @translate
                'info' => 'A type for the resource. Different types have different default properties attached to them.', // @translate
                'empty_option' => '',
            ],
        ]);

        $istCIDOCFieldset->add([
            'name' => 'ist_cidoc_div',
            'type' => Fieldset::class,
            'options' => [
                'label' => 'IST CIDOC2', // @translate
            ],
        ]);

        // $istCIDOCDiv = $istCIDOCFieldset->get('ist_cidoc_div');

        /*$istCIDOCFieldset->add([
            'name' => 'property',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Identifier', // @translate
            ],
            'attributes' => [
                'placeholder' => 'item identifier',
                'class' => 'property field',
            ],
        ]);
        $istCIDOCFieldset->add([
            'name' => 'dcterms:title',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Title/Name', // @translate
            ],
            'attributes' => [
                'placeholder' => 'item title/name', // @translate
                'class' => 'property field',
            ],
        ]);
        $istCIDOCFieldset->add([
            'name' => 'cidoc:P3_has_note',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Description', // @translate
            ],
            'attributes' => [
                'placeholder' => 'item description', // @translate
                'class' => 'property field',
            ],
        ]);
        $istCIDOCFieldset->add([
            'name' => 'cidoc:P25_moved',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Location', // @translate
            ],
            'attributes' => [
                'placeholder' => 'item location', // @translate
                'class' => 'property field',
            ],
        ]);*/

        $inputFilter = $this->getInputFilter();
        foreach ($this as $element) {
            $inputFilter->add([
                'name' => $element->getName(),
                'required' => false,
            ]);
        }
        
        $addEvent = new Event('form.add_elements', $this);
        $this->getEventManager()->triggerEvent($addEvent);


        $inputFilter->add([
            'name' => 'o:resource_class',
            'required' => false,
        ]);

        $filterEvent = new Event('form.add_input_filters', $this, ['inputFilter' => $inputFilter]);
        $this->getEventManager()->triggerEvent($filterEvent);

        /*   NÃO ESQUECER PROCESSAR O TYPE: 'LITERAL', 'URI', ETC.... 
        
        Só para o location -> ter recurso de 'Location' (em vez de items, tal como a procura de items que é só de locations também) com a API do Fenix (semelhante a ASint)
        O resto fazer com texto apenas

        Pôr também um campo de:
           + IST CIDOC
                -> Add item
                -> List items
                -> Search Items



           */
    }
}
