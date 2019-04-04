<?php
namespace ISTCIDOC\DataType;

//use Omeka\DataType\AbstractDataType;
use Omeka\Api\Adapter\AbstractEntityAdapter;
use Omeka\Api\Representation\ValueRepresentation;
use Omeka\Entity\Value;
use Omeka\DataType\Literal as LiteralO;
use Zend\View\Renderer\PhpRenderer;

class Literal extends LiteralO
{
    public function form(PhpRenderer $view)
    {
        return $view->partial('istcidoc/common/data-type/literal');
    }
}
