<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Entity\Notification;

class TwitterNotifier extends AbstractNotifier
{
    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        throw new \Exception("twitternotifier todo.");
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return array(
            'login'    => array('text', array('required' => true)),
            'password' => array('text', array('required' => true))
        );
    }
}
