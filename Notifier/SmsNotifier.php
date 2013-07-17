<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Model\NotificationInterface;

class SmsNotifier extends AbstractNotifier
{
    /**
     * @see AbstractNotifier
     */
    public function send(NotificationInterface $notification)
    {
    }
}
