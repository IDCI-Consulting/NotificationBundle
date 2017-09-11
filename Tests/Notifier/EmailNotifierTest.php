<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Notifier;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use IDCI\Bundle\NotificationBundle\Notifier\EmailNotifier;
use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Entity\NotifierConfiguration;

class EmailNotifierTest extends \PHPUnit_Framework_TestCase
{
    private $notifier;

    private $defaultConfiguration;

    private $defaultDatabaseConfiguration;

    private $mailConfiguration;

    public function setUp()
    {
        $this->defaultConfiguration = array(
            'mirror_link_url' => 'http://notification-manager.test/mirror-link',
            'tracking_url' => 'http://notification-manager.test/tracking',
            'default_configuration' => 'default',
            'configurations' => array(
                'default' => array(
                    'transport' => 'default_smtp',
                    'fromName' => 'default_test',
                    'from' => 'default_test',
                    'replyTo' => 'default_test',
                    'server' => 'default_test.local',
                    'login' => 'default_test',
                    'password' => 'default_test',
                    'port' => 12345,
                    'encryption' => null,
                    'tracking_enabled' => false,
                    'mirror_link_enabled' => false
                )
            )
        );

        $this->mailConfiguration = array(
            'mirror_link_url' => 'http://notification-manager.test/mirror-link',
            'tracking_url' => 'http://notification-manager.test/tracking',
            'transport' => 'mail',
            'fromName' => 'IDCINotificationBundle Unit Test',
            'from' => 'no-reply@idci-consulting.fr',
            'replyTo' => 'no-reply@idci-consulting.fr',
            'tracking_enabled' => false,
            'mirror_link_enabled' => false,
        );

        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $repository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $entityManager
            ->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($repository))
        ;

        $repository
            ->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnCallback(function($args) {
                    $notifierConfiguration = new NotifierConfiguration();
                    $notifierConfiguration->setType('email');

                    if ('email' == $args['type']) {
                        if ('test_sendmail' == $args['alias']) {
                            $notifierConfiguration->setConfiguration(json_encode($this->mailConfiguration));
                        }

                        if ('test_sendmail_tracking' == $args['alias']) {
                            $conf = $this->mailConfiguration;
                            $conf['tracking_enabled'] = true;
                            $notifierConfiguration->setConfiguration(json_encode($conf));
                        }

                        if ('test_sendmail_mirror_link' == $args['alias']) {
                            $conf = $this->mailConfiguration;
                            $conf['mirror_link_enabled'] = true;
                            $notifierConfiguration->setConfiguration(json_encode($conf));
                        }

                        return $notifierConfiguration;
                    }

                    return null;
                }
            ))
        ;

        $this->notifier = new EmailNotifier($entityManager);
        $this->notifier->setDefaultConfiguration($this->defaultConfiguration);
    }

    // Test NotifierInterface methods:

    public function testSendNotification()
    {
        $notification = new Notification();
        $notification
            ->setType('email')
            ->setNotifierAlias('test_sendmail')
            ->setTo(json_encode(array(
                'to' => 'idci_notification_test@yopmail.com'
            )))
            ->setContent(json_encode(array(
                'subject' => 'Test',
                'message' => 'Test message',
                'htmlMessage' => null,
                'attachments' => null,
            )))
        ;

        // Until sendmail is not install in the docker container
        $this->assertFalse($this->notifier->sendNotification($notification));
        //$this->assertTrue($this->notifier->sendNotification($notification));
    }

    public function testGetToFields()
    {
        $expectingKeys = array(
            'to',
            'cc',
            'bcc',
        );

        $configureKeys = array_keys($this->notifier->getToFields());

        asort($expectingKeys);
        asort($configureKeys);

        $this->assertEquals($expectingKeys, $configureKeys);
    }

    public function testGetContentFields()
    {
        $expectingKeys = array(
            'subject',
            'message',
            'htmlMessage',
            'attachments',
        );

        $configureKeys = array_keys($this->notifier->getContentFields());

        asort($expectingKeys);
        asort($configureKeys);

        $this->assertEquals($expectingKeys, $configureKeys);
    }

    public function testGetFromFields()
    {
        $expectingKeys = array(
            'transport',
            'from',
            'fromName',
            'replyTo',
            'server',
            'login',
            'password',
            'port',
            'encryption',
            'tracking_enabled',
            'mirror_link_enabled',
        );

        $configureKeys = array_keys($this->notifier->getFromFields());

        asort($expectingKeys);
        asort($configureKeys);

        $this->assertEquals($expectingKeys, $configureKeys);
    }

    public function testCleanData()
    {
        $data = array(
            'to' => array(
                'to' => 'test@mail.com',
                'cc' => null,
                'bcc' => null,
            ),
            'from' => array(
                'transport' => 'smtp',
                'from' => 'from_value',
                'fromName' => 'from_name_value',
                'replyTo' => 'reply_to_value',
                'server' => 'server.smtp.fr',
                'login' => 'id_value',
                'password' => 'password',
                'port' => 123,
                'encryption' => 'ssl',
                'tracking_enabled' => true,
                'mirror_link_enabled' => false,
            ),
            'content' => array(
                'subject' => 'Test',
                'message' => 'Test message',
                'htmlMessage' => null,
                'attachments' => null,
            ),
        );

        $this->assertEquals(
            $data,
            $this->notifier->cleanData($data)
        );

        // Missing content:subject
        $data = array(
            'to' => array(
                'to' => 'test@mail.com',
                'cc' => null,
                'bcc' => null,
            ),
            'from' => array(
                'transport' => 'smtp',
                'from' => 'from_value',
                'fromName' => 'from_name_value',
                'replyTo' => 'reply_to_value',
                'server' => 'server.smtp.fr',
                'login' => 'id_value',
                'password' => 'password',
                'port' => 123,
                'encryption' => 'ssl',
            ),
            'content' => array(
                'message' => 'Test message',
                'htmlMessage' => null,
                'attachments' => null,
            ),
        );

        try {
            $data = $this->notifier->cleanData($data);
            $this->fail("Expected exception not thrown");
        } catch(\Exception $e) {
            $this->assertInstanceOf('\Symfony\Component\OptionsResolver\Exception\MissingOptionsException', $e);
        }

        // Missing to:to
        $data = array(
            'to' => array(
                'cc' => null,
                'bcc' => null,
            ),
            'from' => array(
                'transport' => 'smtp',
                'from' => 'from_value',
                'fromName' => 'from_name_value',
                'replyTo' => 'reply_to_value',
                'server' => 'server.smtp.fr',
                'login' => 'id_value',
                'password' => 'password',
                'port' => 123,
                'encryption' => 'ssl',
            ),
            'content' => array(
                "subject"     => "Test",
                'message' => 'Test message',
                'htmlMessage' => null,
                'attachments' => null,
            ),
        );

        try {
            $data = $this->notifier->cleanData($data);
            $this->fail("Expected exception not thrown");
        } catch(\Exception $e) {
            $this->assertInstanceOf('\Symfony\Component\OptionsResolver\Exception\MissingOptionsException', $e);
        }
    }

    // Test specific methods:

    public function testBuildMessage()
    {
        $to = array(
            'to' => 'to-idci-notification@yopmail.com',
            'cc' => 'cc-idci-notification@yopmail.com',
            'bcc' => 'bcc-idci-notification@yopmail.com',
        );

        // Without HTML message
        $contentWithoutHtml = array(
            'subject' => 'Test',
            'message' => 'Test message',
            'htmlMessage' => null,
            'attachments' => null,
        );
        $notification = new Notification();
        $notification
            ->setType('email')
            ->setNotifierAlias('test_sendmail')
            ->setTo(json_encode($to))
            ->setContent(json_encode($contentWithoutHtml))
        ;

        $message = $this->notifier->buildMessage($notification);

        $this->assertEquals(
            array($this->mailConfiguration['from'] => $this->mailConfiguration['fromName']),
            $message->getFrom()
        );
        $this->assertEquals(array($to['to'] => null), $message->getTo());
        $this->assertEquals(array($to['cc'] => null), $message->getCc());
        $this->assertEquals(array($to['bcc'] => null), $message->getBcc());
        $this->assertEquals(array($this->mailConfiguration['replyTo'] => null), $message->getReplyTo());
        $this->assertEquals($contentWithoutHtml['subject'], $message->getSubject());
        $this->assertEquals($contentWithoutHtml['message'], $message->getBody());
        $this->assertEquals('text/plain', $message->getContentType());

        // With HTML message and tracking disabled
        $contentWithHtml = array(
            'subject' => 'Test',
            'message' => 'Test message',
            'htmlMessage' => '<h1>Test message</h1>',
            'attachments' => null,
        );
        $notification->setContent(json_encode($contentWithHtml));

        $message = $this->notifier->buildMessage($notification);

        $this->assertEquals('multipart/alternative', $message->getContentType());
        $children = $message->getChildren();
        $this->assertEquals('text/html', $children[0]->getContentType());
        $this->assertEquals($contentWithHtml['htmlMessage'], $children[0]->getBody());

        // With HTML message and tracking enabled
        $notification
            ->setNotifierAlias('test_sendmail_tracking')
            ->setContent(json_encode($contentWithHtml))
        ;

        $message = $this->notifier->buildMessage($notification);

        $this->assertEquals('multipart/alternative', $message->getContentType());
        $children = $message->getChildren();
        $this->assertEquals('text/html', $children[0]->getContentType());
        $this->assertEquals(
            '<h1>Test message</h1><img alt="tracker" src="http://notification-manager.test/tracking/?action=open" width="1" height="1" border="0" />',
            $children[0]->getBody()
        );

        // With HTML message and mirror_link enabled
        $notification
            ->setNotifierAlias('test_sendmail_mirror_link')
            ->setContent(json_encode($contentWithHtml))
        ;

        $message = $this->notifier->buildMessage($notification);

        $this->assertEquals('multipart/alternative', $message->getContentType());
        $children = $message->getChildren();
        $this->assertEquals('text/html', $children[0]->getContentType());
        $this->assertEquals(
            '<a href="http://notification-manager.test/mirror-link/">lien mirroir</a><h1>Test message</h1>',
            $children[0]->getBody()
        );
    }

    public function testGetMailer()
    {
        // Mail
        $mailer = EmailNotifier::getMailer(array('transport' => 'mail'));
        $this->assertInstanceOf('\Swift_MailTransport', $mailer->getTransport());

        // Sendmail
        $mailer = EmailNotifier::getMailer(array('transport' => 'sendmail'));
        $this->assertInstanceOf('\Swift_SendmailTransport', $mailer->getTransport());

        // Smtp
        $mailer = EmailNotifier::getMailer(array(
            'transport' => 'smtp',
            'server' => 'db_test.local',
            'login' => 'db_test',
            'password' => 'db_test',
            'port' => 12345,
            'encryption' => null,
        ));
        $this->assertInstanceOf('\Swift_SmtpTransport', $mailer->getTransport());
    }
}
