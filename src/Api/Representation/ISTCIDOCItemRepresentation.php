<?php
namespace ISTCIDOC\Api\Representation;

use Omeka\Api\Representation\ItemRepresentation;

class ISTCIDOCItemRepresentation extends ItemRepresentation
{
    /**
     * Get the display title for this resource.
     *
     * @param string|null $default
     * @return string|null
     */
    public function displayTitle($default = null)
    {
        $title = $this->value('istcidoc:title', [
            'default' =>  $this->value('dcterms:title'),
        ]);

        if ($title !== null) {
            return (string) $title;
        }

        if ($default === null) {
            $title = $this->value('dcterms:title', [
                'default' =>  null,
            ]);
            $translator = $this->getServiceLocator()->get('MvcTranslator');
            $default = $translator->translate('[Untitled]');
        }
        
        return $default;
    }

    public function getControllerName()
    {
        return 'istcidoc_items';
    }

    public function displayValues(array $options = [])
    {
        if (!isset($options['viewName'])) {
            $options['viewName'] = 'istcidoc/common/resource-values';
        }
        $partial = $this->getViewHelper('partial');

        $eventManager = $this->getEventManager();
        $args = $eventManager->prepareArgs(['values' => $this->values()]);
        $eventManager->trigger('rep.resource.display_values', $this, $args);
        $options['values'] = $args['values'];

        $template = $this->resourceTemplate();
        if ($template) {
            $options['templateProperties'] = $template->resourceTemplateProperties();
        }

        return $partial($options['viewName'], $options);
    }

    public function adminUrl($action = null, $canonical = false)
    {
        $url = $this->getViewHelper('Url');
        return $url(
            'admin/ist-cidoc/resource-id',
            [
                'controller' => $this->getControllerName(),
                'action' => $action,
                'id' => $this->id(),
            ],
            ['force_canonical' => $canonical]
        );
    }

    public function displayDescription($default = null)
    {
        return (string) $this->value('istcidoc:description', [
            'default' => $default,
        ]);
    }

    public function displayLocation($default = null)
    {
        return (string) $this->value('istcidoc:location', [
            'default' => $default,
        ]);

    }

    public function displayIdentifier($default = null)
    {
        return (string) $this->value('istcidoc:identifier', [
            'default' => $default,
        ]);

    }

    public function displayClass($default = null)
    {
        $resourceClass = $this->resourceClass();
        return $resourceClass ? $resourceClass->term() : $default;
    }

    
    public function displayResourceClassTerm($default = null)
    {
        $resourceClass = $this->resourceClass();
        return $resourceClass ? $resourceClass->vocabulary()->prefix() : $default;
    }

    public function isISTCIDOCItem($default = null)
    {
        return $this->displayResourceClassTerm() == 'istcidoc';
    }

}
