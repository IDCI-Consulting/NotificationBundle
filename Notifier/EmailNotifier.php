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

use Symfony\Component\Validator\Constraints as Assert;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

class EmailNotifier extends AbstractNotifier
{
    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        $to = json_decode($notification->getTo(), true);
        $content = json_decode($notification->getContent(), true);
        $configuration = $this->getConfiguration($notification);

        $message = \Swift_Message::newInstance()
            ->setSubject(isset($content['subject']) ? $content['subject'] : null)
            ->setFrom(array($configuration['from'] => $configuration['fromName']))
            ->setReplyTo(isset($configuration['replyTo']) ? $configuration['replyTo'] : null)
            ->setTo($to['to'])
            ->setCc(isset($to['cc']) ? $to['cc'] : null)
            ->setBcc(isset($to['bcc']) ? $to['bcc'] : null)
            ->setBody(isset($content['message']) ? $content['message'] : null)
        ;

        if ($content['htmlMessage']) {
            $message->addPart($content['htmlMessage'], 'text/html');
        }

        $mailer = $this->getMailer($configuration);

        return $mailer->send($message) > 0;
    }

    /**
     * get mailer
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
     * Initialize a sendmail transport
     *
     * @return Swift_SendmailTransport
     */
    protected static function initSendmailTransport()
    {
        return $transport = new \Swift_SendmailTransport();
    }

    /**
     * Initialize a mail transport
     *
     * @return Swift_MailTransport
     */
    protected static function initMailTransport($configuration)
    {
        return $transport = new \Swift_MailTransport();
    }

    /**
     * Initialize a smtp transport
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
            'to'  => array('text', array('required' => true)),
            'cc'  => array('text', array('required' => false)),
            'bcc' => array('text', array('required' => false))
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
            'htmlMessage' => array('textarea', array('required' => false)),
            'attachments' => array('text',     array('required' => false))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return array(
            'transport'    => array('choice', array(
                'required' => false,
                'choices'  => array(
                    'smtp'     => 'smtp',
                    'sendmail' => 'sendmail',
                    'mail'     => 'mail'
                )
            )),
            'from'         => array('text',     array('required' => false)),
            'fromName'     => array('text',     array('required' => false)),
            'replyTo'      => array('text',     array('required' => false)),
            'server'       => array('text',     array('required' => false)),
            'login'        => array('text',     array('required' => false)),
            'password'     => array('password', array('required' => false)),
            'port'         => array('integer',  array('required' => false)),
            'encryption'   => array('choice',   array(
                'required' => false,
                'choices'  => array(
                    'ssl'  => 'ssl',
                    'tls'  => 'tls'
                )
            ))
        );
    }
}
