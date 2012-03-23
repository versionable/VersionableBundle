<?php

namespace UKWM\Bundle\VersionableBundle\Exception;

use UKWM\Bundle\VersionableBundle\Doctrine\Versionable;

final class UnknownVersionException extends \InvalidargumentException
{
    public function __construct($versionNumber, Versionable $entity)
    {
        $this->message = sprintf('Version number %d for ""', $versionNumber, get_class($entity));
    }
}