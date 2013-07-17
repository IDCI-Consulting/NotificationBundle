<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Model;

use IDCI\Bundle\NotificationBundle\Entity\Notification;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class SmsNotification extends AbstractNotification
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
    public function getNotifierServiceName()
    {
        return "sms_notifier";
    }

    /**
     * @see NotificationInterface
     */
    public function toNotification()
    {
        $notification = parent::toNotification()
            ->setTo($this->getTo())
            ->setContent($this->getMessage())
        ;

        return $notification;
    }

    /**
     * @see NotificationInterface
     */
    public function fromNotification(Notification $notificationEntity)
    {
    }

    /**
     * SetTo
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
