<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Entity\Notification;

class MailNotifier extends AbstractNotifier
{
    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        die("mailnotifier");
    }


    /**
     * {@inheritdoc}
     */
    public function getToFields()
    {
        return array(
            'firstName' => array('text',     array('required' => true)),
            'lastName'  => array('text',     array('required' => true)),
            'address'   => array('textarea', array('required' => true)),
            'city'      => array('text',     array('required' => true)),
            'country'   => array('text',     array('required' => true))
        );
    }
}
