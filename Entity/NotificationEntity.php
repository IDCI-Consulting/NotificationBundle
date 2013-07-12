<?php

/**
 * 
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Entity;

use IDCI\Bundle\NotificationBundle\Model\NotificationInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="IDCI\Bundle\NotificationBundle\Repository\NotificationRepository")
 */
class NotificationEntity implements NotificationInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=128, nullable=false)
     */    
    protected $type;
    
    /**
     * @var string
     *
     * @ORM\Column(name="from", type="string", length=128, nullable=false)
     */    
    protected $from;

    /**
     * @var string
     *
     * @ORM\Column(name="to", type="json_array", length=128, nullable=false)
     */
    protected $to;

    /**
     * @var \DateTime
     *     
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */    
    protected $createdAt;
    
    /**
     * @var \DateTime
     *     
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */    
    protected $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=64, nullable=false)
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="json_array", nullable=true)
     */
    protected $content;
    
    /**
     * @var string
     *
     @ORM\Column(name="source", type="string", length=255, nullable=true)
     */
    protected $source;
}
