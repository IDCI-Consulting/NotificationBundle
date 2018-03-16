<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use IDCI\Bundle\NotificationBundle\Exception\NotificationFieldParseErrorException;

/**
 * Notification.
 *
 * @ORM\Entity(repositoryClass="IDCI\Bundle\NotificationBundle\Entity\Repository\NotificationRepository")
 * @ORM\Table(name="notification", indexes={
 *    @ORM\Index(name="notification_status", columns={"status"}),
 *    @ORM\Index(name="notification_source", columns={"source"}),
 *    @ORM\Index(name="notification_alias", columns={"notifier_alias"}),
 *    @ORM\Index(name="notification_created_at", columns={"created_at"}),
 *    @ORM\Index(name="notification_updated_at", columns={"updated_at"})
 * })
 * @ORM\HasLifecycleCallbacks()
 */
class Notification
{
    const STATUS_NEW = 'NEW';
    const STATUS_DONE = 'DONE';
    const STATUS_ERROR = 'ERROR';
    const STATUS_PENDING = 'PENDING';

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=128, nullable=false, unique=true)
     */
    protected $hash;

    /**
     * @var string
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    protected $type;

    /**
     * @var string
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    protected $status;

    /**
     * @var string
     * @ORM\Column(name="notifier_alias", type="string", length=64, nullable=true)
     */
    protected $notifierAlias;

    /**
     * @var string
     * @ORM\Column(name="_from", type="text", nullable=true)
     */
    protected $from;

    /**
     * @var string
     * @ORM\Column(name="_to", type="text", nullable=true)
     */
    protected $to;

    /**
     * @var string
     * @ORM\Column(name="attachments", type="text", nullable=true)
     */
    protected $attachments;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $source;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $log;

    /**
     * @ORM\OneToMany(targetEntity="TrackingHistory", mappedBy="notification", cascade={"all"})
     */
    protected $trackingHistories;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    protected $updatedAt;

    /**
     * Get status list.
     *
     * @return array
     */
    public static function getStatusList()
    {
        return array(
            self::STATUS_NEW => self::STATUS_NEW,
            self::STATUS_DONE => self::STATUS_DONE,
            self::STATUS_ERROR => self::STATUS_ERROR,
            self::STATUS_PENDING => self::STATUS_PENDING,
        );
    }

    /**
     * On create.
     *
     * @ORM\PrePersist()
     */
    public function onCreate()
    {
        $date = new \DateTime('now');
        $this->setCreatedAt($date);
        $this->setUpdatedAt($date);
    }

    /**
     * On update.
     *
     * @ORM\PreUpdate()
     */
    public function onUpdate()
    {
        $date = new \DateTime('now');
        $this->setUpdatedAt($date);
    }

    /**
     * toString.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('[%d] %s',
            $this->getId(),
            $this->getType()
        );
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->setStatus(self::STATUS_NEW);
    }

    /**
     * Get data.
     *
     * @param string $data
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     *
     * @throws NotificationFieldParseErrorException
     */
    public static function getData($data, $key, $default)
    {
        if (null === $key) {
            return $data;
        }

        $decodedData = json_decode($data, true);
        if (null === $decodedData) {
            throw new NotificationFieldParseErrorException($data);
        }

        if (!isset($decodedData[$key])) {
            return $default;
        }

        return $decodedData[$key];
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set hash.
     *
     * @param string $hash
     *
     * @return Notification
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash.
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return Notification
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set notifierAlias.
     *
     * @param string $notifierAlias
     *
     * @return Notification
     */
    public function setNotifierAlias($notifierAlias)
    {
        $this->notifierAlias = $notifierAlias;

        return $this;
    }

    /**
     * Get notifierAlias.
     *
     * @return string
     */
    public function getNotifierAlias()
    {
        return $this->notifierAlias;
    }

    /**
     * Has notifierAlias.
     *
     * @return bool
     */
    public function hasNotifierAlias()
    {
        return null !== $this->getNotifierAlias();
    }

    /**
     * Set from.
     *
     * @param string $from
     *
     * @return Notification
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get from.
     *
     * @param string|null $key
     * @param mixed|null  $default
     *
     * @return mixed|null
     */
    public function getFrom($key = null, $default = null)
    {
        return self::getData($this->from, $key, $default);
    }

    /**
     * Get from json decoded.
     *
     * @return array
     *
     * @throws NotificationFieldParseErrorException
     */
    public function getFromDecoded()
    {
        $fromValues = json_decode($this->getFrom(), true);
        if (null === $fromValues) {
            throw new NotificationFieldParseErrorException($this->getFrom());
        }

        return $fromValues;
    }

    /**
     * Set to.
     *
     * @param mixed $to
     *
     * @return Notification
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get to.
     *
     * @param string|null $key
     * @param mixed|null  $default
     *
     * @return array
     */
    public function getTo($key = null, $default = null)
    {
        return self::getData($this->to, $key, $default);
    }

    /**
     * Set attachments.
     *
     * @param array $attachments
     *
     * @return Notification
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * Get attachments.
     *
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Add attachment
     *
     * @param string $attachment
     *
     * @return Notification
     */
    public function addAttachment($attachment)
    {
        $this->attachments = $attachment;

        return $this;
    }

    /**
     * Get to json decoded.
     *
     * @return array
     *
     * @throws NotificationFieldParseErrorException
     */
    public function getToDecoded()
    {
        $toValues = json_decode($this->getTo(), true);
        if (null === $toValues) {
            throw new NotificationFieldParseErrorException($this->getTo());
        }

        return $toValues;
    }

    /**
     * Set content.
     *
     * @param array $content
     *
     * @return Notification
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @param string|null $key
     * @param mixed|null  $default
     *
     * @return array
     */
    public function getContent($key = null, $default = null)
    {
        return self::getData($this->content, $key, $default);
    }

    /**
     * Get content json decoded.
     *
     * @return array
     *
     * @throws NotificationFieldParseErrorException
     */
    public function getContentDecoded()
    {
        $contentValues = json_decode($this->getContent(), true);
        if (null === $contentValues) {
            throw new NotificationFieldParseErrorException($this->getContent());
        }

        return $contentValues;
    }

    /**
     * Set source.
     *
     * @param string $source
     *
     * @return Notification
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set log.
     *
     * @param string $log
     *
     * @return Notification
     */
    public function setLog($log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log.
     *
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Add log.
     *
     * @param string $log
     *
     * @return Notification
     */
    public function addLog($log)
    {
        $now = new \DateTime('now');
        $this->setLog(sprintf('%s: %s'.PHP_EOL.PHP_EOL.'%s',
            $now->format('Y-m-d H:i:s'),
            $log,
            $this->getLog()
        ));

        return $this;
    }

    /**
     * Add trackingHistory.
     *
     * @param \IDCI\Bundle\NotificationBundle\Entity\TrackingHistory $trackingHistory
     *
     * @return Notification
     */
    public function addTrackingHistory(\IDCI\Bundle\NotificationBundle\Entity\TrackingHistory $trackingHistory)
    {
        $this->trackingHistories[] = $trackingHistory;

        return $this;
    }

    /**
     * Remove trackingHistory.
     *
     * @param \IDCI\Bundle\NotificationBundle\Entity\TrackingHistory $trackingHistory
     */
    public function removeTrackingHistory(\IDCI\Bundle\NotificationBundle\Entity\TrackingHistory $trackingHistory)
    {
        $this->trackingHistories->removeElement($trackingHistory);
    }

    /**
     * Get trackingHistories.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrackingHistories()
    {
        return $this->trackingHistories;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Notification
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return Notification
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
