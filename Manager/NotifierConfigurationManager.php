<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use IDCI\Bundle\NotificationBundle\Event\NotifierConfigurationEvent;
use IDCI\Bundle\NotificationBundle\Event\NotifierConfigurationEvents;

class NotifierConfigurationManager extends AbstractManager
{
    /**
     * Get Repository
     *
     * @return \Doctrine\ORM\EntityManager\EntityRepository
     */
    protected function getRepository()
    {
        return $this->getObjectManager()->getRepository("IDCINotificationBundle:NotifierConfiguration");
    }

    /**
     * Add
     * Use the object manager to add (persist) the given object
     *
     * @param object $entity
     */
    public function add($entity)
    {
        $this->getEventDispatcher()->dispatch(
            NotifierConfigurationEvents::PRE_CREATE,
            new NotifierConfigurationEvent($entity)
        );

        parent::add($entity);

        $this->getEventDispatcher()->dispatch(
            NotifierConfigurationEvents::POST_CREATE,
            new NotifierConfigurationEvent($entity)
        );
    }

    /**
     * Update
     * Use the object manager to update (persist) the given object
     *
     * @param object $entity
     */
    public function update($entity)
    {
        $this->getEventDispatcher()->dispatch(
            NotifierConfigurationEvents::PRE_UPDATE,
            new NotifierConfigurationEvent($entity)
        );

        parent::update($entity);

        $this->getEventDispatcher()->dispatch(
            NotifierConfigurationEvents::POST_UPDATE,
            new NotifierConfigurationEvent($entity)
        );
    }

    /**
     * Delete
     * Use the object manager to delete (remove) the given object
     *
     * @param object $entity
     */
    public function delete($entity)
    {
        $this->getEventDispatcher()->dispatch(
            NotifierConfigurationEvents::PRE_DELETE,
            new NotifierConfigurationEvent($entity)
        );

        parent::delete($entity);

        $this->getEventDispatcher()->dispatch(
            NotifierConfigurationEvents::POST_DELETE,
            new NotifierConfigurationEvent($entity)
        );
    }
}