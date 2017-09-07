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

    public function setUp()
    {
        $this->defaultConfiguration = array(
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

        $this->defaultDatabaseConfiguration = array(
            'transport' => 'db_smtp',
            'fromName' => 'db_test',
            'from' => 'db_test',
            'replyTo' => 'db_test',
            'server' => 'db_test.local',
            'login' => 'db_test',
            'password' => 'db_test',
            'port' => 12345,
            'encryption' => null,
            'tracking_enabled' => false,
            'mirror_link_enabled' => false
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
                    if ('email' == $args['type'] && 'my_alias_test' == $args['alias']) {
                        $notifierConfiguration = new NotifierConfiguration();
                        $notifierConfiguration
                            ->setType('email')
                            ->setConfiguration(json_encode($this->defaultDatabaseConfiguration))
                        ;

                        return $notifierConfiguration;
                    }

                    if ('wrong_json' == $args['alias']) {
                        $notifierConfiguration = new NotifierConfiguration();
                        $notifierConfiguration
                            ->setType('email')
                            ->setConfiguration('{"bad_json": bad:(}')
                        ;

                        return $notifierConfiguration;
                    }

                    return null;
                }
            ))
        ;

        $this->notifier = new EmailNotifier($entityManager, $this->defaultConfiguration);
    }

    // Test NotifierInterface methods:

    public function testSendNotification()
    {
    }

    public function testGetConfiguration()
    {
        $notification = new Notification();

        // Default
        $this->assertEquals(
            $this->defaultConfiguration['configurations']['default'],
            $this->notifier->getConfiguration($notification)
        );

        // Notification from setted
        $itemFrom = array(
            'transport' => 'from_smtp',
            'fromName' => 'from_test',
            'from' => 'from_test',
        );
        $notification->setFrom(json_encode($itemFrom));
        $this->assertEquals($itemFrom, $this->notifier->getConfiguration($notification));

        // Notification from setted but not valid json
        $notification->setFrom('{"bad_json": bad:(}');
        try {
            $configuration = $this->notifier->getConfiguration($notification);
            $this->fail("Expected exception not thrown");
        } catch(\Exception $e) {
            $this->assertInstanceOf('IDCI\Bundle\NotificationBundle\Exception\ConfigurationParseErrorException', $e);
        }

        // Notification alias undefined
        $notification->setNotifierAlias('dummy');
        try {
            $configuration = $this->notifier->getConfiguration($notification);
            $this->fail("Expected exception not thrown");
        } catch(\Exception $e) {
            $this->assertInstanceOf('IDCI\Bundle\NotificationBundle\Exception\UndefinedNotifierConfigurationException', $e);
        }

        // Notification alias defined but not the right type
        $notification->setNotifierAlias('my_alias_test');
        try {
            $configuration = $this->notifier->getConfiguration($notification);
            $this->fail("Expected exception not thrown");
        } catch(\Exception $e) {
            $this->assertInstanceOf('IDCI\Bundle\NotificationBundle\Exception\UndefinedNotifierConfigurationException', $e);
        }

        // Valid json from database (alias and type ok)
        $notification->setType('email');
        $this->assertEquals(
            $this->defaultDatabaseConfiguration,
            $this->notifier->getConfiguration($notification)
        );

        // Wrong json from Database
        $notification->setNotifierAlias('wrong_json');
        try {
            $configuration = $this->notifier->getConfiguration($notification);
            $this->fail("Expected exception not thrown");
        } catch(\Exception $e) {
            $this->assertInstanceOf('IDCI\Bundle\NotificationBundle\Exception\ConfigurationParseErrorException', $e);
        }
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
        $notification = new Notification();
        $notification
            ->setType('email')
            ->setFrom(json_encode(array(
                'transport' => 'smtp',
                'from' => 'dummy@email.com',
                'fromName' => 'dummy@email.com',
                'replyTo' => 'dummy@email.com',
                'server' => 'server.smtp.fr',
                'login' => 'id_value',
                'password' => 'password',
                'port' => 123,
                'encryption' => 'ssl',
                'tracking_enabled' => true,
                'mirror_link_enabled' => true,
            )))
            ->setTo(json_encode(array(
                'to' => 'test@mail.com',
                'cc' => null,
                'bcc' => null,
            )))
            ->setContent(json_encode(array(
                'subject' => 'Test',
                'message' => 'Test message',
                'htmlMessage' => null,
                'attachments' => null,
            )))
        ;

        $configuration = $this->notifier->getConfiguration($notification);
        //var_dump($configuration); die;
        /*
        $to = json_decode($this->notification->getTo(), true);
        $content = json_decode($this->notification->getContent(), true);

        $message = \Swift_Message::newInstance()
            ->setSubject(isset($content['subject']) ? $content['subject'] : null)
            ->setFrom(array($configuration['from'] => $configuration['fromName']))
            ->setReplyTo(isset($configuration['replyTo']) ? $configuration['replyTo'] : null)
            ->setTo($to['to'])
            ->setCc(isset($to['cc']) ? $to['cc'] : null)
            ->setBcc(isset($to['bcc']) ? $to['bcc'] : null)
            ->setBody(isset($content['message']) ? $content['message'] : null)
        ;

        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals($message->getSubject(), $this->notifier->buildMessage($this->notification)->getSubject());
        */
    }

    public function testBuildHTMLContent()
    {
        /*
        $this->assertEquals(
            '<img alt="picto" src="http://dummy_url?notification_id=&action=open" width="1" height="1" border="0" />',
            $this->notifier->buildHTMLContent($this->notification)
        );
        */
    }

    public function testAddTracker()
    {
    /*
        $this->assertEquals(
            '<img alt="picto" src="http://dummy_url?notification_id=&action=open" width="1" height="1" border="0" />',
            $this->notifier->addTracker($this->notification)
        );

        $this->notification->setFrom(json_encode(array(
            'transport' => 'smtp',
            'from' => 'dummy@email.com',
            'fromName' => 'dummy@email.com',
            'replyTo' => 'dummy@email.com',
            'server' => 'server.smtp.fr',
            'login' => 'id_value',
            'password' => 'password',
            'port' => 123,
            'encryption' => 'ssl',
            'track' => false,
        )));

        $this->assertEquals('', $this->notifier->addTracker($this->notification));
        */
    }
}
