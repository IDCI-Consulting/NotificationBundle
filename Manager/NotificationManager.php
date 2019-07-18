<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Notifier\NotifierInterface;
use IDCI\Bundle\NotificationBundle\Event\NotificationEvent;
use IDCI\Bundle\NotificationBundle\Event\NotificationEvents;
use IDCI\Bundle\NotificationBundle\Exception\UndefinedNotifierException;
use IDCI\Bundle\NotificationBundle\Exception\NotificationParametersException;

class NotificationManager extends AbstractManager
{
    /**
     * @var array
     */
    protected $notifiers;

    /**
     * @param string
     */
    protected $filesDirectory;

    /**
     * Constructor.
     *
     * @param ObjectManager            $objectManager
     * @param EventDispatcherInterface $entityManager
     * @param string                   $filesDirectory
     */
    public function __construct(
        ObjectManager $objectManager,
        EventDispatcherInterface $eventDispatcher,
        $filesDirectory
    ) {
        parent::__construct($objectManager, $eventDispatcher);
        $this->notifiers = array();
        $this->filesDirectory = $filesDirectory;
    }

    /**
     * Get Repository.
     *
     * @return \Doctrine\ORM\EntityManager\EntityRepository
     */
    protected function getRepository()
    {
        return $this->getObjectManager()->getRepository('IDCINotificationBundle:Notification');
    }

    /**
     * Add
     * Use the object manager to add (persist) the given object.
     *
     * @param object $entity
     */
    public function add($entity)
    {
        $this->getEventDispatcher()->dispatch(
            NotificationEvents::PRE_CREATE,
            new NotificationEvent($entity)
        );

        parent::add($entity);

        $this->getEventDispatcher()->dispatch(
            NotificationEvents::POST_CREATE,
            new NotificationEvent($entity)
        );
    }

    /**
     * Update
     * Use the object manager to update (persist) the given object.
     *
     * @param object $entity
     */
    public function update($entity)
    {
        $this->getEventDispatcher()->dispatch(
            NotificationEvents::PRE_UPDATE,
            new NotificationEvent($entity)
        );

        parent::update($entity);

        $this->getEventDispatcher()->dispatch(
            NotificationEvents::POST_UPDATE,
            new NotificationEvent($entity)
        );
    }

    /**
     * Delete
     * Use the object manager to delete (remove) the given object.
     *
     * @param object $entity
     */
    public function delete($entity)
    {
        $this->getEventDispatcher()->dispatch(
            NotificationEvents::PRE_DELETE,
            new NotificationEvent($entity)
        );

        parent::delete($entity);

        $this->getEventDispatcher()->dispatch(
            NotificationEvents::POST_DELETE,
            new NotificationEvent($entity)
        );
    }

    /**
     * Get notifiers.
     *
     * @return array
     */
    public function getNotifiers()
    {
        return $this->notifiers;
    }

    /**
     * Add notifier.
     *
     * @param NotifierInterface $notifier
     * @param string            $alias
     *
     * @return NotificationManager
     */
    public function addNotifier(NotifierInterface $notifier, $alias)
    {
        $this->notifiers[$alias] = $notifier;

        return $this;
    }

    /**
     * Get notifier.
     *
     * @param string $alias
     *
     * @return NotifierInterface
     */
    public function getNotifier($alias)
    {
        if (!isset($this->notifiers[$alias])) {
            throw new UndefinedNotifierException($alias);
        }

        return $this->notifiers[$alias];
    }

    /**
     * Add Notification.
     *
     * @param string      $alias      notifier alias
     * @param string      $data       notification data in json format
     * @param array       $files      notification files
     * @param string|null $sourceName notification source name
     * @param int         $priority   the notification priority
     */
    public function addNotification(
        $alias,
        $data,
        array $files,
        $sourceName = null,
        $priority = Notification::PRIORITY_NORMAL
    ) {
        $notifier = $this->getNotifier($alias);

        $decodedData = json_decode($data, true);
        if (!$decodedData) {
            throw new NotificationParametersException(sprintf('Json decode failed with the given data: %s', $data));
        }

        if (!empty($files) && null === $this->filesDirectory) {
            throw new NotificationParametersException('The parameters \'idci_notification.files_directory\' is not configured');
        }
        $data = $notifier->cleanData($decodedData);
        $movedFiles = $this->moveFiles($alias, $files);

        $notification = new Notification();
        $notification
            ->setType($alias)
            ->setNotifierAlias(isset($data['notifierAlias']) ? $data['notifierAlias'] : null)
            ->setSource(null === $sourceName ? $data['source'] : $sourceName)
            ->setFrom(isset($data['from']) ? json_encode($data['from']) : null)
            ->setTo(isset($data['to']) ? json_encode($data['to']) : null)
            ->setContent(json_encode($data['content']))
            ->setFiles($movedFiles)
            ->setPriority($priority)
        ;

        $this->add($notification);
    }

    /**
     * Move files.
     *
     * @param string $alias
     * @param array  $files
     *
     * @return array The files data (path + mime info)
     */
    public function moveFiles($alias, array $files)
    {
        $info = array();
        $now = new \Datetime();
        $directory = $this->filesDirectory.DIRECTORY_SEPARATOR.$now->format('Ymd');

        foreach ($files as $k => $uploadedFile) {
            $filename = sprintf('%s_%s', $alias, uniqid(mt_rand(1, 9999), true));
            $info[] = array(
                'path' => $directory.DIRECTORY_SEPARATOR.$filename,
                'name' => $uploadedFile->getClientOriginalName(),
                'mimeType' => $uploadedFile->getMimeType(),
                'extension' => $uploadedFile->guessExtension(),
            );
            $uploadedFile->move($directory, $filename);
            unset($uploadedFile);
        }

        return $info;
    }

    /**
     * Notify.
     *
     * @param Notification $notification
     */
    public function notify(Notification $notification)
    {
        $notifier = $this->getNotifier($notification->getType());
        try {
            $notifier->sendNotification($notification);
            $notification->setStatus(Notification::STATUS_DONE);
        } catch (\Exception $e) {
            $notification->setStatus(Notification::STATUS_ERROR);
            $notification->addLog($e->getMessage());
        }
        $this->update($notification);
    }
}
