<?php
namespace ISTCIDOC\DataType;

use ISTCIDOC\Api\Representation\LocationRepresentation;
use Omeka\Api\Adapter\AbstractEntityAdapter;
use Omeka\DataType\Literal;
use Omeka\Entity\Value;
use Zend\Form\Element\Select;
use Zend\View\Renderer\PhpRenderer;

class Location extends Literal
{
    public function getName()
    {
        return 'location';
    }

    public function getLabel()
    {
        return 'Label'; // @translate
    }

    public function getOptgroupLabel()
    {
        return 'Location'; // @translate
    }

    public function getUri()
    {
        return $this->location->uri();
    }
	
	public function getLocal()
    {
        return $this->location->local();
    }
	
	public function getPosition()
    {
        return $this->location->position();
    }
}
