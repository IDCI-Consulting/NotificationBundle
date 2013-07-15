<?php

/**
 * 
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Model;

use IDCI\Bundle\NotificationBundle\Entity\NotificationEntity;


class EmailNotification extends NotificationEntity
{    
    protected $to;
    protected $cc;
    protected $bcc;
    protected $message;
    protected $attachement;

}
