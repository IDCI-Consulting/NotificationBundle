<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Proxy\NotificationInterface;

class TwitterNotifier extends AbstractNotifier
{
    /**
     * Constructor
     *
     * @see AbstractNotifier
     */
    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    /**
     * @see AbstractNotifier
     */
    public function send(NotificationInterface $proxyNotification)
    {
        var_dump($proxyNotification);
    }
}
