<?php

namespace UKWM\Bundle\VersionableBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UKWM\Bundle\VersionableBundle\Entity\Snapshot
 */
class Snapshot
{
    /**
     * @var string $entityClass
     */
    private $entityClass;

    /**
     * @var string $entityId
     */
    private $entityId;

    /**
     * @var array $snapshot
     */
    private $snapshot;

    /**
     * @var string $version
     */
    private $version;

    /**
     * @var datetime $createdAt
     */
    private $createdAt;

    /**
     * @var integer $id
     */
    private $id;

    public function __construct($entityClass = null, $entityId = null, $snapshot = null, $version = null)
    {
        $this->entityClass = $entityClass;
        $this->entityId = $entityId;
        $this->snapshot = $snapshot;
        $this->version = $version;
        $this->createdAt = new \DateTime();
    }

    /**
     * Set entityClass
     *
     * @param string $entityClass
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    /**
     * Get entityClass
     *
     * @return string 
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Set entityId
     *
     * @param string $entityId
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
    }

    /**
     * Get entityId
     *
     * @return string 
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set snapshot
     *
     * @param array $snapshot
     */
    public function setSnapshot($snapshot)
    {
        $this->snapshot = $snapshot;
    }

    /**
     * Get snapshot
     *
     * @return array 
     */
    public function getSnapshot()
    {
        return $this->snapshot;
    }

    /**
     * Set version
     *
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Get version
     *
     * @return string 
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}