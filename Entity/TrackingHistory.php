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


}
