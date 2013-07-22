<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Proxy;

use IDCI\Bundle\NotificationBundle\Entity\Notification;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class SmsProxyNotification extends AbstractProxyNotification
{
    /**
     * @Assert\NotBlank()
     */
    protected $to;

    /**
     * @Assert\NotBlank()
     */
    protected $message;

    /**
     * @see NotificationInterface
     */
    public function getTypeName()
    {
        return "sms";
    }

    /**
     * @see NotificationInterface
     */
    public function getNotifierServiceName()
    {
        return "sms_notifier";
    }

    /**
     * @see NotificationInterface
     */
    public function getNotification()
    {
        $this->notification
            ->setTo($this->getTo())
            ->setContent($this->getMessage())
        ;

        return parent::getNotification();
    }

    /**
     * @see NotificationInterface
     */
    public function setNotification(Notification $notification)
    {
        $this
            ->setTo($notification->getTo())
            ->setMessage($notification->getContent())
        ;

        return parent::setNotification($notification);
    }

    /**
     * Set To
     *
     * @param string $to
     * @return SmsNotification
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get to
     *
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return SmsNotification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
