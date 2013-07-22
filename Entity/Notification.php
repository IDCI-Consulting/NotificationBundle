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

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="IDCI\Bundle\NotificationBundle\Repository\NotificationRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Notification
{
    const STATUS_NEW = "NEW";
    const STATUS_DONE = "DONE";
    const STATUS_ERROR = "ERROR";
    const STATUS_PENDING = "PENDING";

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=128, nullable=false)
     */
    protected $type;

    /**
     * @var string
     * @ORM\Column(name="_from", type="string", length=128, nullable=true)
     */
    protected $from;

    /**
     * @var string
     * @ORM\Column(name="_to", type="json_array", length=128, nullable=false)
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
     * @ORM\Column(name="status", type="string", length=64, nullable=false)
     */
    protected $status;

    /**
     * @var string
     * @ORM\Column(name="content", type="json_array", nullable=true)
     */
    protected $content;

    /**
     * @var string
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     */
    protected $source;

    /**
     * @var string
     * @ORM\Column(name="log", type="text", nullable=true)
     */
    protected $log;

    /**
     * @ORM\PrePersist()
     */
    public function onCreate()
    {
        $this->setCreatedAt(new \DateTime('now'));
        $this->setUpdatedAt(new \DateTime('now'));
        $this->setStatus(parent::STATUS_NEW);
    }

    /**
     * onUpdate
     * @ORM\PreUpdate()
     */
    public function onUpdate()
    {
        $this->setUpdatedAt(new \DateTime('now'));
    }

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Get id
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
     * Set to
     *
     * @param array $to
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
}
