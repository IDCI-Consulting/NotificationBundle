<?php

/**
 * 
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Model;


class FacebookNotification implements NotificationInterface
{
    protected $to;
    protected $message;
    
    public function convertToNotification()
    {
    }
}
