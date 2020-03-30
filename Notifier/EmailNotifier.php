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
use IDCI\Bundle\NotificationBundle\Exception\ConfigurationParseErrorException;
use Symfony\Component\Form\Extension\Core\Type as Types;

class EmailNotifier extends AbstractNotifier
{
    /**
     * {@inheritdoc}
     */
    public function getToFields()
    {
        return array(
            'to' => array(Types\EmailType::class, array('required' => true, 'trim' => true)),
            'cc' => array(Types\EmailType::class, array('required' => false, 'trim' => true)),
            'bcc' => array(Types\EmailType::class, array('required' => false, 'trim' => true)),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getContentFields()
    {
        return array(
            'subject' => array(Types\TextType::class, array('required' => true)),
            'message' => array(Types\TextareaType::class, array('required' => false)),
            'htmlMessage' => array(Types\TextareaType::class, array('required' => false)),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return array(
            'transport' => array(Types\ChoiceType::class, array(
                'required' => false,
                'choices' => array(
                    'smtp' => 'smtp',
                    'sendmail' => 'sendmail',
                    'mail' => 'mail',
                ),
            )),
            'from' => array(Types\EmailType::class, array('required' => false, 'trim' => true)),
            'fromName' => array(Types\TextType::class, array('required' => false)),
            'replyTo' => array(Types\TextType::class, array('required' => false)),
            'server' => array(Types\TextType::class, array('required' => false)),
            'login' => array(Types\TextType::class, array('required' => false)),
            'password' => array(Types\PasswordType::class, array('required' => false)),
            'port' => array(Types\IntegerType::class, array('required' => false)),
            'encryption' => array(Types\ChoiceType::class, array(
                'required' => false,
                'choices' => array(
                    'ssl' => 'ssl',
                    'tls' => 'tls',
                ),
            )),
            'tracking_enabled' => array(Types\CheckboxType::class, array('required' => false)),
            'mirror_link_enabled' => array(Types\CheckboxType::class, array('required' => false)),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        $mailer = self::getMailer($this->getConfiguration($notification));

        return $mailer->send($this->buildMessage($notification)) > 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function getNotificationConfiguration(Notification $notification)
    {
        if (null !== $notification->getFrom()) {
            $from = json_decode($notification->getFrom(), true);
            if (null === $from) {
                throw new ConfigurationParseErrorException($notification->getFrom());
            }

            if (null !== $from['transport']) {
                return $from;
            }
        }

        return false;
    }

    /**
     * get mailer.
     *
     * @param array $configuration
     *
     * @return Swift_Mailer
     */
    public static function getMailer(array $configuration)
    {
        $initTransportMethod = sprintf('init%sTransport',
            ucfirst(strtolower($configuration['transport']))
        );
        $transport = self::$initTransportMethod($configuration);

        return \Swift_Mailer::newInstance($transport);
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
            ->setFrom(array($configuration['from'] => isset($configuration['fromName']) ? $configuration['fromName'] : $configuration['from']))
            ->setReplyTo(isset($configuration['replyTo']) ? $configuration['replyTo'] : null)
            ->setTo($to['to'])
            ->setCc(isset($to['cc']) ? $to['cc'] : null)
            ->setBcc(isset($to['bcc']) ? $to['bcc'] : null)
            ->setBody(isset($content['message']) ? $content['message'] : null)
        ;

        if (isset($content['htmlMessage'])) {
            $message->addPart($this->buildHTMLContent($notification), 'text/html');
        }

        foreach ($notification->getFiles() as $file) {
            $message->attach(\Swift_Attachment::fromPath($file['path'])->setFilename($file['name']));
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
    protected function buildHTMLContent(Notification $notification)
    {
        $content = json_decode($notification->getContent(), true);
        $htmlContent = $content['htmlMessage'];

        $this
            ->buildMirrorLink($notification, $htmlContent)
            ->buildTracker($notification, $htmlContent)
        ;

        return $htmlContent;
    }

    /**
     * Build html tag "<a href>" to allow viewing the notification in a web browser.
     *
     * @param Notification $notification
     * @param string       $content
     *
     * @return self
     */
    protected function buildMirrorLink(Notification $notification, &$content)
    {
        $configuration = $this->getConfiguration($notification);

        if (!isset($configuration['mirror_link_enabled']) || !$configuration['mirror_link_enabled']) {
            $content = self::purgeMirrorLink($content);

            return $this;
        }

        $count = 0;
        $mirrorLink = sprintf(
            '%s/%s',
            $this->defaultConfiguration['mirror_link_url'],
            $notification->getHash()
        );

        $content = preg_replace(array('#\[\[mirrorlink\]\]#'), $mirrorLink, $content, -1, $count);

        if (0 === $count) {
            $content = sprintf(
                '<a href="%s">lien mirroir</a>%s',
                $mirrorLink,
                $content
            );
        }

        return $this;
    }

    /**
     * Build html tag "<img src>" to track readed email with notification.
     *
     * @param Notification $notification
     * @param string       $content
     *
     * @return self
     */
    protected function buildTracker(Notification $notification, &$content)
    {
        $configuration = $this->getConfiguration($notification);

        if (!isset($configuration['tracking_enabled']) || !$configuration['tracking_enabled']) {
            return $this;
        }

        $imgTracker = sprintf(
            '<img alt="tracker" src="%s/%s?action=%s" width="1" height="1" border="0" />',
            $this->defaultConfiguration['tracking_url'],
            $notification->getHash(),
            'open'
        );

        $content = preg_replace(array('#</body>#'), $imgTracker.'</body>', $content, 1, $count);

        if (0 === $count) {
            $content .= $imgTracker;
        }

        return $this;
    }

    /**
     * Purge the mirror link if present.
     *
     * @param string $content
     */
    public static function purgeMirrorLink($content)
    {
        return preg_replace(
            array('#<a[^>]*href=\"\[\[mirrorlink\]\]\".*<\/a>#U'),
            '',
            $content
        );
    }

    /**
     * Initialize a mail transport.
     *
     * @param array $configuration
     *
     * @return Swift_MailTransport
     */
    protected static function initMailTransport($configuration)
    {
        return $transport = new \Swift_MailTransport();
    }

    /**
     * Initialize a sendmail transport.
     *
     * @param array $configuration
     *
     * @return Swift_SendmailTransport
     */
    protected static function initSendmailTransport($configuration)
    {
        return $transport = new \Swift_SendmailTransport();
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
}
