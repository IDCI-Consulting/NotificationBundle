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
            ->setBody($content['message'])
        ;

        $this->getMailer()->send($message);
    }

    /**
     * {@inheritdoc}
     */
    public function dataValidationMap()
    {
        return array(
            'to' => array(
                'to'  => new Assert\Email(),
                'cc'  => new Assert\Email(),
                'bcc' => new Assert\Email()
            ),
            'content' => array(
                'subject' => new Assert\NotBlank(),
                'message' => new Assert\NotBlank()
            )
        );
    }
}
