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

class SmsNotifier extends AbstractNotifier
{
    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        throw new \Exception("smsnotifier todo.");
    }

    /**
     * {@inheritdoc}
     */
    public function getToFields()
    {
        return array(
            'to'  => array('text', array('required' => true))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return array(
            'from'         => array('text', array('required' => true)),
            'alias' => array('text', array('required' => true))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getContentFields()
    {
        return array(
            'message' => array('textarea', array('required' => false))
        );
    }
}
