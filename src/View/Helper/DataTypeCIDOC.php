<?php
namespace ISTCIDOC\View\Helper;

use Omeka\DataType\Manager as DataTypeManager;
use Zend\Form\Element\Select;
use Omeka\View\Helper\DataType;
use Zend\View\Helper\AbstractHelper;

/**
 * View helper for rendering data types.
 */
class DataTypeCIDOC extends DataType
{	
    public function getTemplates()
    {
        $view = $this->getView();
        $templates = '';
        foreach ($this->dataTypes as $dataType) {
            $templates .= $view->partial('istcidoc/common/data-type-wrapper', [
                'dataType' => $dataType,
                'resource' => isset($view->resource) ? $view->resource : null,
            ]);
        }
        return $templates;
    }
}
