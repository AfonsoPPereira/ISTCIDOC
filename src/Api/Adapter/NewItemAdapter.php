<?php
namespace ISTCIDOC\Api\Adapter;

use Doctrine\ORM\QueryBuilder;
use Omeka\Api\Adapter\ItemAdapter;
use Omeka\Api\Exception;
use Omeka\Api\Request;
use Omeka\Entity\EntityInterface;
use Omeka\Stdlib\ErrorStore;

class NewItemAdapter extends ItemAdapter
{
    /**
     * {@inheritDoc}
     */
    public function getRepresentationClass()
    {
        return 'ISTCIDOC\Api\Representation\ISTCIDOCItemRepresentation';
    }
}
