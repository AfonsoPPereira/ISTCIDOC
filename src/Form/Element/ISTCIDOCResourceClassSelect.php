<?php
namespace ISTCIDOC\Form\Element;

use Omeka\Form\Element\ResourceClassSelect;
use ISTCIDOC\Files\ISTCIDOCTerm;

class ISTCIDOCResourceClassSelect extends ResourceClassSelect
{	
    public function getValueOptions()
    {
        $events = $this->getEventManager();

        $resourceName = $this->getResourceName();

        $termAsValue = $this->getOption('term_as_value');
        $query = $this->getOption('query');

        if (!is_array($query)) {
            $query = [];
        }
        if (!isset($query['sort_by'])) {
            $query['sort_by'] = 'label';
        }

        $args = $events->prepareArgs(['query' => $query]);
        $events->trigger('form.vocab_member_select.query', $this, $args);
        $query = $args['query'];

        $valueOptions = [];

        $response = $this->getApiManager()->search($resourceName, $query);
        foreach ($response->getContent() as $member) {
            if ($member->vocabulary()->prefix() === ISTCIDOCTerm::PREFIX){
                $attributes = ['data-term' => $member->term()];

                if ('properties' === $resourceName) {
                    $attributes['data-property-id'] = $member->id();
                } elseif ('resource_classes' === $resourceName) {
                    $attributes['data-resource-class-id'] = $member->id();
                }
                $option = [
                    'label' => $member->label(),
                    'value' => $termAsValue ? $member->term() : $member->id(),
                    'attributes' => $attributes,
                ];
                $vocabulary = $member->vocabulary();
                if (!isset($valueOptions[$vocabulary->prefix()])) {
                    $valueOptions[$vocabulary->prefix()] = [
                        'label' => $vocabulary->label(),
                        'options' => [],
                    ];
                }
                $valueOptions[$vocabulary->prefix()]['options'][] = $option;
            }
        }

        $prependValueOptions = $this->getOption('prepend_value_options');
        if (is_array($prependValueOptions)) {
            $valueOptions = $prependValueOptions + $valueOptions;
        }

        $args = $events->prepareArgs(['valueOptions' => $valueOptions]);
        $events->trigger('form.vocab_member_select.value_options', $this, $args);
        $valueOptions = $args['valueOptions'];

        return $valueOptions;
    }
}