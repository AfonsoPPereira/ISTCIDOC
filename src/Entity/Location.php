<?php
namespace ISTCIDOC\Entity;

use Omeka\Entity\AbstractEntity;

/**
 * @Entity
 */
class Location extends AbstractEntity
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column(unique=true, type="bigint")
     */
    protected $uri;

    /**
     * @Column(type="string", length=190)
     */
    protected $local;

    /**
     * @Column(type="string", length=190)
     */
    protected $position;


    public function getId()
    {
        return $this->id;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setLocal($local)
    {
        $this->local = $local;
    }

    public function getLocal()
    {
        return $this->local;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
    }

}
