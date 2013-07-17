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

class EmailNotifier extends AbstractNotifier
{
    protected $mailer;

    /**
     * constructor
     *
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * get mailer
     *
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * @see AbstractNotifier
     */
    public function send(NotificationInterface $notification)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($notification->getSubject())
            ->setFrom($notification->getFrom())
            ->setTo($notification->getTo())
            ->setBody($notification->getMessage())
        ;

        $this->getMailer()->send($message);
    }
}
