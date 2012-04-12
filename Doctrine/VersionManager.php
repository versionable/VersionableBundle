<?php

namespace UKWM\Bundle\VersionableBundle\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

use UKWM\Bundle\VersionableBundle\Exception\UnknownVersionException;

class VersionManager
{
    private $_em;
    
    /**
     * @param EntityManager $em 
     */
    public function __construct(EntityManager $em)
    {
       $this->_em = $em; 
    }
    
    /**
     * Gets all version for given entity
     * 
     * @param Versionable $entity
     * @return array 
     */
    public function getVersions(Versionable $entity)
    {
        $className = get_class($entity);
        $class = $this->_em->getClassMetadata($className);
        $id = current($class->getIdentifiervalues($entity));
        
        $query = $this->_em->createQuery(
            "SELECT v FROM UKWM\Bundle\VersionableBundle\Entity\Snapshot v INDEX BY v.version ".
            "WHERE v.entityClass = :entityClass AND v.entityId = :id ORDER BY v.version DESC");
        $query->setParameter('entityClass', $className);
        $query->setParameter('id', $id);
        
        $versions = array();
        foreach ($query->getResult() as $version) {
            $versions[$version->getVersion()] = $version;
        }
        
        return $versions;
    }
    
    /**
     * Set values to those in snapshot at given value
     * 
     * @param Versionable $entity
     * @param int $versionNumber
     * @throws \InvalidArgumentException 
     */
    public function revert(Versionable $entity, $versionNumber)
    {
        $versions = $this->getVersions($entity);
        
        if (!isset($versions[$versionNumber])) {
            throw new UnknownVersionException($versionNumber, $entity);
        }
        
        $version = $versions[$versionNumber];
        
        $class = $this->_em->getClassMetadata(get_class($entity));
        
        foreach ($version->getSnapshot() as $field => $value) {
            $class->reflFields[$field]->setValue($entity, $value);
        }
    }
}