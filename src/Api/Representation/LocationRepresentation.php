<?php
namespace ISTCIDOC\Api\Representation;

use Omeka\Api\Representation\AbstractEntityRepresentation;

class LocationRepresentation extends AbstractEntityRepresentation
{
    public function getControllerName()
    {
        return 'location';
    }

    public function getJsonLdType()
    {
        return 'o:Location';
    }

    public function getJsonLd()
    {
        return [
            'o:uri' => $this->uri(),
            'o:local' => $this->local(),
            'o:position' => $this->position(),
        ];
    }

    public function uri()
    {
        return $this->resource->getUri();
    }

    public function local()
    {
        return $this->resource->getLocal();
    }

    public function position()
    {
        return $this->resource->getPosition();
    }

    public function url($action = null, $canonical = false)
    {
        $status = $this->getServiceLocator()->get('Omeka\Status');
        $routeMatch = $status->getRouteMatch();
        $url = null;
        if ($status->isAdminRequest()) {
            $url = $this->adminUrl($action, $canonical);
        }

        return $url;
    }

    public function adminUrl($action = null, $canonical = false)
    {
        $url = $this->getViewHelper('Url');
        return $url(
            'admin/ist-cidoc/id',
            [
                'controller' => $this->getControllerName(),
                'action' => $action,
                'id' => $this->id(),
            ],
            ['force_canonical' => $canonical]
        );
    }

    public function valueRepresentation()
    {
        $representation = [];
        $representation['@id'] = $this->apiUrl();
        $representation['type'] = 'location';
        $representation['value_resource_id'] = $this->id();
        $representation['value_resource_name'] = $this->local();
        $representation['url'] = $this->url();
        $representation['display_title'] = $this->local();

        return $representation;
    }
}
