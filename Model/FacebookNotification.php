<?php

/**
 * 
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


class FacebookNotification extends AbstractNotification
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
     * @see AbstractNotification
     */
    public function convertToNotification()
    {
        $notification = parent::convertToNotification()
            ->setTo($this->getTo())
            ->setContent($this->getMessage())
        ;

        return $notification;
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
