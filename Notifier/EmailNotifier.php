<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @author:  Rémy MENCE <remy.mence@gmail.com>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Entity\Notification;

class EmailNotifier extends AbstractNotifier
{
    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        $mailer = $this->getMailer($this->getConfiguration($notification));

        return $mailer->send($this->buildMessage($notification)) > 0;
    }

    /**
     * Build the message that will be sent by the mailer.
     *
     * @param Notification $notification
     *
     * @return string
     */
    public function buildMessage(Notification $notification)
    {
        $configuration = $this->getConfiguration($notification);
        $to = json_decode($notification->getTo(), true);
        $content = json_decode($notification->getContent(), true);

        $message = \Swift_Message::newInstance()
            ->setSubject(isset($content['subject']) ? $content['subject'] : null)
            ->setFrom(array($configuration['from'] => $configuration['fromName']))
            ->setReplyTo(isset($configuration['replyTo']) ? $configuration['replyTo'] : null)
            ->setTo($to['to'])
            ->setCc(isset($to['cc']) ? $to['cc'] : null)
            ->setBcc(isset($to['bcc']) ? $to['bcc'] : null)
            ->setBody(isset($content['message']) ? $content['message'] : null)
        ;

        if (isset($content['htmlMessage'])) {
            $message->addPart($this->buildHTMLContent($notification), 'text/html');
        }

        return $message;
    }

    /**
     * Build html content.
     *
     * @param Notification $notification
     *
     * @return string
     */
    public function buildHTMLContent(Notification $notification)
    {
        $content = json_decode($notification->getContent(), true);

        return sprintf(
            '%s%s',
            $content['htmlMessage'],
            $this->addTracker($notification)
        );
    }

    /**
     * Build html tag "<img>" to track readed email with notification.
     *
     * @param Notification $notification
     *
     * @return string
     */
    public function addTracker(Notification $notification)
    {
        $configuration = $this->getConfiguration($notification);

        if (!$configuration['tracking_enabled']) {
            return '';
        }

        return sprintf(
            '<img alt="picto" src="%s?notification_id=%s&action=%s" width="1" height="1" border="0" />',
            //$this->defaultConfiguration['tracking_url'],
            $notification->getId(),
            'open'
        );
    }

    /**
     * get mailer.
     *
     * @param array $configuration
     *
     * @return Swift_Mailer
     */
    protected function getMailer(array $configuration)
    {
        $initTransportMethod = sprintf('init%sTransport',
            ucfirst(strtolower($configuration['transport']))
        );
        $transport = self::$initTransportMethod($configuration);

        return \Swift_Mailer::newInstance($transport);
    }

    /**
     * Initialize a sendmail transport.
     *
     * @return Swift_SendmailTransport
     */
    protected static function initSendmailTransport()
    {
        return $transport = new \Swift_SendmailTransport();
    }

    /**
     * Initialize a mail transport.
     *
     * @return Swift_MailTransport
     */
    protected static function initMailTransport($configuration)
    {
        return $transport = new \Swift_MailTransport();
    }

    /**
     * Initialize a smtp transport.
     *
     * @param array $configuration
     *
     * @return Swift_SmtpTransport
     */
    protected static function initSmtpTransport($configuration)
    {
        $transport = new \Swift_SmtpTransport();

        $transport
            ->setHost($configuration['server'])
            ->setPort($configuration['port'])
            ->setEncryption($configuration['encryption'])
            ->setUsername($configuration['login'])
            ->setPassword($configuration['password'])
        ;

        return $transport;
    }

    /**
     * {@inheritdoc}
     */
    public function getToFields()
    {
        return array(
            'to' => array('email', array('required' => true, 'trim' => true)),
            'cc' => array('email', array('required' => false, 'trim' => true)),
            'bcc' => array('email', array('required' => false, 'trim' => true)),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getContentFields()
    {
        return array(
            'subject' => array('text', array('required' => true)),
            'message' => array('textarea', array('required' => false)),
            'htmlMessage' => array('textarea', array('required' => false)),
            'attachments' => array('text', array('required' => false)),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return array(
            'transport' => array('choice', array(
                'required' => false,
                'choices' => array(
                    'smtp' => 'smtp',
                    'sendmail' => 'sendmail',
                    'mail' => 'mail',
                ),
            )),
            'from' => array('email', array('required' => false, 'trim' => true)),
            'fromName' => array('text', array('required' => false)),
            'replyTo' => array('text', array('required' => false)),
            'server' => array('text', array('required' => false)),
            'login' => array('text', array('required' => false)),
            'password' => array('password', array('required' => false)),
            'port' => array('integer', array('required' => false)),
            'encryption' => array('choice', array(
                'required' => false,
                'choices' => array(
                    'ssl' => 'ssl',
                    'tls' => 'tls',
                ),
            )),
            'tracking_enabled' => array('checkbox', array('required' => false)),
            'mirror_link_enabled' => array('checkbox', array('required' => false)),
        );
    }
}
