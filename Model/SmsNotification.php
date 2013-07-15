<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Model;

class SmsNotification implements NotificationInterface
{    
    protected $to;
    protected $message;

    public function convertToNotification()
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
