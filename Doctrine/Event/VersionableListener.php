<?php

namespace UKWM\Bundle\VersionableBundle\Doctrine\Event;

use Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Events,
    Doctrine\ORM\Event\OnFlushEventArgs,
    Doctrine\ORM\EntityManager,
    UKWM\Bundle\VersionableBundle\Doctrine\Versionable,
    UKWM\Bundle\VersionableBundle\Entity\Snapshot;

use UKWM\Bundle\VersionableBundle\Exception\EntityNotVersionedException,
    UKWM\Bundle\VersionableBundle\Exception\SingleIdentifierRequiredException;

/**
 * Doctrine event listener to detect versionable entities and create a snapshot
 * of the current dataset and store as a new version record 
 */
class VersionableListener implements EventSubscriber
{
    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(Events::onFlush);
    }
    
    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $unitOfWork = $em->getUnitOfWork();
        
        $resourceClass = $em->getClassMetaData('UKWM\Bundle\VersionableBundle\Entity\Snapshot');
        
        foreach ($unitOfWork->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof Versionable) {
                $entityClass = $em->getClassMetadata(get_class($entity));
                
                if (!$entityClass->isVersioned) {
                    throw new EntityNotVersionedException();
                }
                
                $entityId = $entityClass->getIdentifierValues($entity);
                
                if (count($entityId) == 1 && current($entityId)) {
                    $entityId = current($entityId);
                } else {
                    throw new SingleIdentifierRequiredException();
                }
                
                $oldValues = array_map(function($changeSetField) {
                    return $changeSetField[0];
                }, $unitOfWork->getEntityChangeset($entity));
                
                $entityVersion = $entityClass->reflFields[$entityClass->versionField]->getValue($entity);
                
                unset($oldValues[$entityClass->versionField]);
                unset($oldValues[$entityClass->getSingleIdentifierFieldName()]);
                
                $version = new Snapshot($entityClass->name, $entityId, $oldValues, $entityVersion);
                
                $em->persist($version);
                $unitOfWork->computeChangeset($resourceClass, $version);
            }
        }
    }
}