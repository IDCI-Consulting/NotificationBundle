<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Entity\Notification;
use Symfony\Component\Form\Extension\Core\Type as Types;

class MailNotifier extends AbstractNotifier
{
    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        throw new \Exception('mailnotifier todo.');
    }

    /**
     * {@inheritdoc}
     */
    public function getToFields()
    {
        return array(
            'firstName' => array(Types\TextType::class, array('required' => true)),
            'lastName' => array(Types\TextType::class, array('required' => true)),
            'address' => array(Types\TextareaType::class, array('required' => true)),
            'postalCode' => array(Types\TextType::class, array('required' => true)),
            'city' => array(Types\TextType::class, array('required' => true)),
            'country' => array(Types\TextType::class, array('required' => true)),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return array(
            'firstName' => array(Types\TextType::class, array('required' => false)),
            'lastName' => array(Types\TextType::class, array('required' => false)),
            'address' => array(Types\TextareaType::class, array('required' => false)),
            'postalCode' => array(Types\TextType::class, array('required' => false)),
            'city' => array(Types\TextType::class, array('required' => false)),
            'country' => array(Types\TextType::class, array('required' => false)),
        );
    }
}
