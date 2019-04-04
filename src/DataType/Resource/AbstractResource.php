<?php
namespace ISTCIDOC\DataType\Resource;

use Omeka\Api\Adapter\AbstractEntityAdapter;
use Omeka\Api\Exception;
use Omeka\Api\Representation\ValueRepresentation;
use Omeka\DataType\Resource\AbstractResource as AbstractResourceO;
use Omeka\DataType\AbstractDataType;
use Omeka\Entity\Value;
use Zend\View\Renderer\PhpRenderer;
use Omeka\Stdlib\Message;

abstract class AbstractResource extends AbstractResourceO
{
    public function form(PhpRenderer $view)
    {
        return $view->partial('istcidoc/common/data-type/resource', [
            'dataType' => $this->getName(),
            'resource' => $view->resource,
        ]);
    }
}
