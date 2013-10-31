<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use Symfony\Component\Validator\Constraints as Assert;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

class EmailNotifier extends AbstractNotifier
{
    protected $mailer;

    /**
     * Constructor
     *
     * @see AbstractNotifier
     * @param Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Get mailer
     *
     * @return Swift_Mailer
     */
    protected function getMailer()
    {
        return $this->mailer;
    }

    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        $to = json_decode($notification->getTo(), true);
        $content = json_decode($notification->getContent(), true);

        $message = \Swift_Message::newInstance()
            ->setSubject($content['subject'])
            // To fix
            ->setFrom('noreplyclient@tessi.fr')
            ->setTo($to['to'])
            ->setCc($to['cc'])
            ->setBcc($to['bcc'])
            ->setBody($content['message'])
        ;

        $this->getMailer()->send($message);
    }

    /**
     * {@inheritdoc}
     */
    public function getToFields()
    {
        return array(
            'to'  => array('text', array('required' => true)),
            'cc'  => array('text', array('required' => false)),
            'bcc' => array('text', array('required' => false)),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getContentFields()
    {
        return array(
            'subject'     => array('text',     array('required' => true)),
            'message'     => array('textarea', array('required' => false)),
            'attachments' => array('text',     array('required' => false)),
        );
    }
}
