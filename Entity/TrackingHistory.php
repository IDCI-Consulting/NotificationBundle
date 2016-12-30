<?php

/**
 *
 * @author:  Rémy MENCE <remymence@gmail.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use IDCI\Bundle\NotificationBundle\Exception\NotificationFieldParseErrorException;

/**
 * TrackingHistory
 *
 * @ORM\Entity(repositoryClass="IDCI\Bundle\NotificationBundle\Entity\Repository\TrackingHistoryRepository")
 * @ORM\Table(name="tracking_history")
 * @ORM\HasLifecycleCallbacks()
 */
class TrackingHistory
{

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
    protected $action;

    /**
     * @var string
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    protected $origin;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    protected $context;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Notification", inversedBy="trackingHistories")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $notification;

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
     * Set action
     *
     * @param string $action
     *
     * @return TrackingHistory
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set origin
     *
     * @param string $origin
     *
     * @return TrackingHistory
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set context
     *
     * @param string $context
     *
     * @return TrackingHistory
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TrackingHistory
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
     * Set notification
     *
     * @param \IDCI\Bundle\NotificationBundle\Entity\Notification $notification
     *
     * @return TrackingHistory
     */
    public function setNotification(\IDCI\Bundle\NotificationBundle\Entity\Notification $notification = null)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return \IDCI\Bundle\NotificationBundle\Entity\Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }
}
