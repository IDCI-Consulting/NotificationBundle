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

class FacebookProxyNotification extends AbstractProxyNotification
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
        return "facebook";
    }

    /**
     * @see NotificationInterface
     */
    public function getNotifierServiceName()
    {
        return "facebook_notifier";
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
        $to      = $notification->getTo();
        $content = $notification->getContent();

        $this
            ->setTo($to['to'])
            ->setMessage($content['message'])
        ;

        return parent::setNotification($notification);
    }

    /**
     * SetTo
     *
     * @param string $to
     * @return FacebookNotification
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
     * @return FacebookNotification
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
