<?php
namespace ISTCIDOC\DataType;

use Omeka\Api\Adapter\AbstractEntityAdapter;
use Omeka\Api\Representation\ValueRepresentation;
use Omeka\Entity\Value;
use Omeka\DataType\Uri as UriO;
use Zend\View\Renderer\PhpRenderer;

class Uri extends UriO
{
    public function form(PhpRenderer $view)
    {
        return $view->partial('istcidoc/common/data-type/uri');
    }
}
