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
}
