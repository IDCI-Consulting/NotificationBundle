<?php

/**
 * 
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Model;

use IDCI\Bundle\NotificationBundle\Entity\NotificationEntity;

class TwitterNotification extends NotificationEntity
{    
    protected $to;
    protected $message;
}
