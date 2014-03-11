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
            //->setFrom($from[array('login', 'password', 'server', 'port', 'encryption', 'isSecured')]);
            ->setTo($to['to'])
            ->setCc($to['cc'])
            ->setBcc($to['bcc'])
            ->setBody($content['message'])
        ;

        if ($content['htmlMessage']) {
            $message->addPart($content['htmlMessage'], 'text/html');
        }

        $mailer = $this->getMailer($this->getConfiguration($notification));

        return $mailer->send($message) > 0;
    }

    /**
     * get mailer
     *
     * @param array $configuration
     *
     * @return Swift_Mailer
     */
    public function getMailer(array $configuration)
    {
        $transport = new \Swift_SmtpTransport();

        $transport
            ->setHost($configuration['server'])
            ->setPort($configuration['port'])
            ->setEncryption($configuration['encryption'])
            ->setUsername($configuration['login'])
            ->setPassword($configuration['password'])
        ;

        return \Swift_Mailer::newInstance($transport);
    }

    /**
     * Check content fields of configuration
     *
     * @param array $notification
     *
     * @return boolean
     */
    public function checkFieldsConfiguration(array $notification)
    {
        $result = false;
        foreach ($notification as $field) {
            if (!$field) {
                $result = true;
            }
        }

        return $result;
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
            'htmlMessage' => array('textarea', array('required' => false)),
            'attachments' => array('text',     array('required' => false)),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return array(
            'login'        => array('text',     array('required' => true)),
            'password'     => array('password', array('required' => true)),
            'server'       => array('text',     array('required' => true)),
            'port'         => array('integer',  array('required' => false)),
            'encryption'   => array('choice',   array('required' => false, 'choices' => array('ssl' => 'ssl', 'tsl' => 'tsl'))),
            'isSecured'    => array('checkbox', array('required' => false)),
            'alias' => array('text',     array('required' => true))
        );
    }
}
