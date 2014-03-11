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
            'firstName'  => array('text',     array('required' => true)),
            'lastName'   => array('text',     array('required' => true)),
            'address'    => array('textarea', array('required' => true)),
            'postalCode' => array('text',     array('required' => true)),
            'city'       => array('text',     array('required' => true)),
            'country'    => array('text',     array('required' => true))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return array(
            'senderFirstName'  => array('text',     array('required' => true)),
            'senderLastName'   => array('text',     array('required' => true)),
            'senderAddress'    => array('textarea', array('required' => true)),
            'senderPostalCode' => array('text',     array('required' => true)),
            'senderCity'       => array('text',     array('required' => true)),
            'senderCountry'    => array('text',     array('required' => true)),
            'alias'     => array('text',     array('required' => true)),
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
