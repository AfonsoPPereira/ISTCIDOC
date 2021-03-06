<?php
namespace ISTCIDOC\Api\Adapter;

use Doctrine\ORM\QueryBuilder;
use Omeka\Api\Exception;
use Omeka\Api\Request;
use Omeka\Entity\EntityInterface;
use Omeka\Stdlib\ErrorStore;

class ISTCIDOCItemAdapter extends NewItemAdapter
{
    /**
     * {@inheritDoc}
     */
    public function getResourceName()
    {
        return 'istcidoc_items';
    }
}
