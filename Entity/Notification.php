<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use IDCI\Bundle\NotificationBundle\Exception\NotificationFieldParseErrorException;

/**
 * Notification
 *
 * @ORM\Entity(repositoryClass="IDCI\Bundle\NotificationBundle\Entity\Repository\NotificationRepository")
 * @ORM\Table(name="notification", indexes={
 *    @ORM\Index(name="notification_status", columns={"status"}),
 *    @ORM\Index(name="notification_source", columns={"source"})
 * })
 * @ORM\HasLifecycleCallbacks()
 */
class Notification
{
    const STATUS_NEW      = "NEW";
    const STATUS_DONE     = "DONE";
    const STATUS_ERROR    = "ERROR";
    const STATUS_PENDING  = "PENDING";

    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    protected $type;

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
     * @var string
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    protected $status;

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
     * Get status list
     *
     * @return array
     */
    public static function getStatusList()
    {
        return array(
            self::STATUS_NEW => self::STATUS_NEW,
            self::STATUS_DONE => self::STATUS_DONE,
            self::STATUS_ERROR => self::STATUS_ERROR,
            self::STATUS_PENDING => self::STATUS_PENDING
        );
    }

    /**
     * On create
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
     * On update
     *
     * @ORM\PreUpdate()
     */
    public function onUpdate()
    {
        $date = new \DateTime('now');
        $this->setUpdatedAt($date);
    }

    /**
     * toString
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
     * Constructor
     */
    public function __construct()
    {
        $this->setStatus(Notification::STATUS_NEW);
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

    /**
     * Set type
     *
     * @param string $type
     * @return NotificationEntity
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set notifierAlias
     *
     * @param string $notifierAlias
     * @return NotificationEntity
     */
    public function setNotifierAlias($notifierAlias)
    {
        $this->notifierAlias = $notifierAlias;

        return $this;
    }

    /**
     * Get notifierAlias
     *
     * @return string
     */
    public function getNotifierAlias()
    {
        return $this->notifierAlias;
    }

    /**
     * Has notifierAlias
     *
     * @return booleen
     */
    public function hasNotifierAlias()
    {
        return null !== $this->getNotifierAlias();
    }

    /**
     * Set from
     *
     * @param string $from
     * @return NotificationEntity
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get from
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Get from json decoded
     *
     * @return array
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
     * Set to
     *
     * @param mixed $to
     * @return NotificationEntity
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get to
     *
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Get to json decoded
     *
     * @return array
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return NotificationEntity
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return NotificationEntity
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return NotificationEntity
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set content
     *
     * @param array $content
     * @return NotificationEntity
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get content json decoded
     *
     * @return array
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
     * Set source
     *
     * @param string $source
     * @return NotificationEntity
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set log
     *
     * @param string $log
     * @return NotificationEntity
     */
    public function setLog($log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log
     *
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Add log
     *
     * @param string $log
     * @return NotificationEntity
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

}
