<?php
namespace ISTCIDOC\Form\Element;

use Omeka\Form\Element\AbstractVocabularyMemberSelect;
use Omeka\Api\Exception\NotFoundException;

class ISTCIDOCPropertySelect extends AbstractVocabularyMemberSelect
{
    public function getResourceName()
    {
        return 'properties';
    }
    /**
     * Get value options for properties.
     *
     * If the "apply_templates" option is set, get only the properties of the
     * configured resource templates and include alternate labels, if any.
     * Otherwise get the default value options.
     *
     * @return array
     */
    public function getValueOptions()
    {
        $valueOptions = Array ( 
            "istcidoc_locations" => Array ( 
                "label" => "Location Properties", "options" => Array ( 
                    0 => Array ( "label" => "Uri", "value" => "uri"), 
                    1 => Array ( "label" => "Location", "value" => "local"), 
                    2 => Array ( "label" => "Position", "value" => "position")
                )));

        return $valueOptions;
    }
}
