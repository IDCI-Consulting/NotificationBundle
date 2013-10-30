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

class EmailNotifier extends AbstractNotifier
{
    protected $mailer;

    /**
     * Constructor
     *
     * @see AbstractNotifier
     * @param Swift_Mailer $mailer
     */
    public function __construct(\Doctrine\ORM\EntityManager $entityManager, \Swift_Mailer $mailer)
    {
        parent::__construct($entityManager);
        $this->mailer = $mailer;
    }

    /**
     * get mailer
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * @see AbstractNotifier
     */
    public function send(NotificationInterface $proxyNotification)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($proxyNotification->getSubject())
            // To fix
            ->setFrom('no-reply@tessi.fr')
            ->setTo($proxyNotification->getTo())
            ->setBody($proxyNotification->getMessage())
        ;

        $this->getMailer()->send($message);
    }
}
