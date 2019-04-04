<?php
namespace ISTCIDOC\DataType\Resource;

class Item extends AbstractResource
{
    public function getName()
    {
        return 'resource:item';
    }

    public function getLabel()
    {
        return 'Item'; // @translate
    }
}
