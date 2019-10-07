<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Entity\Notification;
use Symfony\Component\Form\Extension\Core\Type as Types;

class FacebookNotifier extends AbstractNotifier
{
    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        throw new \Exception('facebooknotifier todo.');
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return array(
            'login' => array(Types\TextType::class, array('required' => true)),
            'password' => array(Types\PasswordType::class, array('required' => true)),
        );
    }
}
