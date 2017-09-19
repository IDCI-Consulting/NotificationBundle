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

class AbstractNotifierTest extends \PHPUnit_Framework_TestCase
{
    private $notifier;

    private $defaultConfiguration;

    private $defaultDatabaseConfiguration;

    public function setUp()
    {
        $this->defaultConfiguration = array(
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
                    'mirror_link_enabled' => false,
                ),
            ),
        );

        $this->defaultDatabaseConfiguration = array(
            'tracking_url' => 'http://notification-manager.test/tracking',
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
            ->will($this->returnCallback(function ($args) {
                $notifierConfiguration = new NotifierConfiguration();
                $notifierConfiguration->setType('email');

                if ('email' == $args['type'] && 'my_alias_test' == $args['alias']) {
                    $notifierConfiguration->setConfiguration(json_encode($this->defaultDatabaseConfiguration));

                    return $notifierConfiguration;
                }

                if ('wrong_json' == $args['alias']) {
                    $notifierConfiguration->setConfiguration('{"bad_json": bad:(}');

                    return $notifierConfiguration;
                }

                return null;
            }
            ))
        ;

        $this->notifier = new EmailNotifier($entityManager);
        $this->notifier->setDefaultConfiguration($this->defaultConfiguration);
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
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertInstanceOf('IDCI\Bundle\NotificationBundle\Exception\ConfigurationParseErrorException', $e);
        }

        // Notification alias undefined
        $notification->setNotifierAlias('dummy');
        try {
            $configuration = $this->notifier->getConfiguration($notification);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertInstanceOf('IDCI\Bundle\NotificationBundle\Exception\UndefinedNotifierConfigurationException', $e);
        }

        // Notification alias defined but not the right type
        $notification->setNotifierAlias('my_alias_test');
        try {
            $configuration = $this->notifier->getConfiguration($notification);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
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
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertInstanceOf('IDCI\Bundle\NotificationBundle\Exception\ConfigurationParseErrorException', $e);
        }
    }
}
