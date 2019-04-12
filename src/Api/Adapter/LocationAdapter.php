<?php
namespace ISTCIDOC\Api\Adapter;

use Doctrine\ORM\QueryBuilder;
use Omeka\Api\Adapter\AbstractEntityAdapter;
use Omeka\Api\Exception;
use Omeka\Api\Request;
use Omeka\Entity\EntityInterface;
use Omeka\Stdlib\ErrorStore;

class LocationAdapter extends AbstractEntityAdapter
{
    /**
     * {@inheritDoc}
     */
    public function getResourceName()
    {
        return 'locations';
    }

    /**
     * {@inheritDoc}
     */
    public function getRepresentationClass()
    {
        return 'ISTCIDOC\Api\Representation\LocationRepresentation';
    }

    public function getEntityClass()
    {
        return 'ISTCIDOC\Entity\Location';
    }

    public function hydrate(Request $request, EntityInterface $entity,
        ErrorStore $errorStore
    ) {
        if ($this->shouldHydrate($request, 'o:uri')) {
            $entity->setUri($request->getValue('o:uri'));
        }
        if ($this->shouldHydrate($request, 'o:local')) {
            $entity->setLocal($request->getValue('o:local'));
        }
        if ($this->shouldHydrate($request, 'o:position')) {
            $entity->setPosition($request->getValue('o:position'));
        }
    }

    public function validateEntity(EntityInterface $entity,
        ErrorStore $errorStore
    ) {
        $uri = $entity->getUri();

        if (false == is_numeric(trim($uri))) {
            $errorStore->addError('o:uri', 'The URI must be an integer only.'); // @translate
        }elseif (false == trim($uri)) {
            $errorStore->addError('o:uri', 'The URI cannot be empty.'); // @translate
        }elseif (!$this->isUnique($entity, ['uri' => $uri])) {
            $errorStore->addError('o:uri', 'The URI is already taken.'); // @translate
        }

        if (false == trim($entity->getLocal())) {
            $errorStore->addError('o:local', 'The location cannot be empty.'); // @translate
        }

        if (false == trim($entity->getPosition())) {
            $errorStore->addError('o:position', 'The position cannot be empty.'); // @translate
        }
    }

     public function buildQuery(QueryBuilder $qb, array $query)
    {
        if (isset($query['id']) && is_numeric($query['id'])) {
            $qb->andWhere($qb->expr()->eq(
                $this->getEntityClass() . '.id',
                $this->createNamedParameter($qb, $query['id'])
            ));
        }

        if (isset($query['search'])) {
            $qb->orWhere($qb->expr()->eq(
                $this->getEntityClass() . '.id',
                $this->createNamedParameter($qb, $query['search'])
            ));
            $qb->orWhere($qb->expr()->like(
                $this->getEntityClass() . '.uri',
                $this->createNamedParameter($qb, '%' . $query['search'] . '%')
            ));
            $qb->orWhere($qb->expr()->like(
                $this->getEntityClass() . '.local',
                $this->createNamedParameter($qb, '%' . $query['search'] . '%')
            ));
            $qb->orWhere($qb->expr()->like(
                $this->getEntityClass() . '.position',
                $this->createNamedParameter($qb, '%' . $query['search'] . '%')
            ));
        }

        if (!isset($query['property']) || !is_array($query['property'])) {
            return;
        }

        $valuesJoin = $this->getEntityClass();
        $where = '';

        foreach ($query['property'] as $queryRow) {
            if (!(is_array($queryRow)
                && array_key_exists('property', $queryRow)
                && array_key_exists('type', $queryRow)
            )) {
                continue;
            }
            $propertyName = $queryRow['property'];
            $queryType = $queryRow['type'];
            $joiner = isset($queryRow['joiner']) ? $queryRow['joiner'] : null;
            $value = isset($queryRow['text']) ? $queryRow['text'] : null;

            if (!$value && $queryType !== 'nex' && $queryType !== 'ex') {
                continue;
            }

            $valuesAlias = $this->createAlias();
            $positive = true;

            switch ($queryType) {
                case 'neq':
                    $positive = false;
                case 'eq':
                    $param = $this->createNamedParameter($qb, $value);
                    $predicateExpr = $qb->expr()->eq(
                        $this->getEntityClass() . '.' . $propertyName,
                        $param
                    );
                    break;
                case 'nin':
                    $positive = false;
                case 'in':
                    $predicateExpr = $qb->expr()->like(
                        $this->getEntityClass() . '.' . $propertyName,
                        $this->createNamedParameter($qb, '%' . $value . '%')
                    );
                    break;
                case 'nex':
                    $positive = false;
                case 'ex':
                    $predicateExpr = $qb->expr()->isNotNull("$valuesAlias.id");
                    break;
                default:
                    continue 2;
            }

            $joinConditions = [];

            if ($positive) {
                $whereClause = '(' . $predicateExpr . ')';
            } else {
                $joinConditions[] = $predicateExpr;
                $whereClause = $qb->expr()->isNull("$valuesAlias.id");
            }

            /*if ($joinConditions) {
                $qb->leftJoin($valuesJoin, $valuesAlias, 'WITH', $qb->expr()->andX(...$joinConditions));
            } else {
                $qb->leftJoin($valuesJoin, $valuesAlias);
            }*/

            if ($where == '') {
                $where = $whereClause;
            } elseif ($joiner == 'or') {
                $where .= " OR $whereClause";
            } else {
                $where .= " AND $whereClause";
            }
        }
        if ($where) {
            $qb->andWhere($where);
        }   
    }
}
