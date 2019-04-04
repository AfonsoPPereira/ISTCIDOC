<?php
namespace ISTCIDOC\DataType\Resource;

class Media extends AbstractResource
{
    public function getName()
    {
        return 'resource:media';
    }

    public function getLabel()
    {
        return 'Media'; // @translate
    }
}
