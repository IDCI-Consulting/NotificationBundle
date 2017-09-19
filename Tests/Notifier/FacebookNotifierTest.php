<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Notifier;

use Doctrine\ORM\EntityManager;
use IDCI\Bundle\NotificationBundle\Notifier\FacebookNotifier;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

class FacebookNotifierTest extends \PHPUnit_Framework_TestCase
{
    private $notifier;

    private $defaultConfiguration;

    public function setUp()
    {
        $this->defaultConfiguration = array(
            'tracking_url' => 'http://notification-manager.test/tracking',
            'default_configuration' => 'default',
            'configurations' => array(
                'default' => array(
                    'login' => 'default_login',
                    'password' => 'default_password',
                ),
            ),
        );

        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->notifier = new FacebookNotifier($entityManager);
        $this->notifier->setDefaultConfiguration(array());
    }

    // Test NotifierInterface methods:

    public function testSendNotification()
    {
        // TODO
    }

    public function testGetConfiguration()
    {
    }

    public function testGetToFields()
    {
        $expectingKeys = array(
            'to',
        );

        $configureKeys = array_keys($this->notifier->getToFields());

        asort($expectingKeys);
        asort($configureKeys);

        $this->assertEquals($expectingKeys, $configureKeys);
    }

    public function testGetContentFields()
    {
        $expectingKeys = array(
            'message',
        );

        $configureKeys = array_keys($this->notifier->getContentFields());

        asort($expectingKeys);
        asort($configureKeys);

        $this->assertEquals($expectingKeys, $configureKeys);
    }

    public function testGetFromFields()
    {
        $expectingKeys = array(
            'login',
            'password',
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
            ),
            'from' => array(
                'login' => 'from_login',
                'password' => 'from_password',
            ),
            'content' => array(
                'message' => 'Test message',
            ),
        );

        $this->assertEquals(
            $data,
            $this->notifier->cleanData($data)
        );

        // Missing from:login
        $data = array(
            'to' => array(
                'to' => 'test@mail.com',
            ),
            'from' => array(
                'password' => 'from_password',
            ),
            'content' => array(
                'message' => 'Test message',
            ),
        );

        try {
            $data = $this->notifier->cleanData($data);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Symfony\Component\OptionsResolver\Exception\MissingOptionsException', $e);
        }

        // Missing from:password
        $data = array(
            'to' => array(
                'to' => 'test@mail.com',
            ),
            'from' => array(
                'login' => 'from_login',
            ),
            'content' => array(
                'message' => 'Test message',
            ),
        );

        try {
            $data = $this->notifier->cleanData($data);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Symfony\Component\OptionsResolver\Exception\MissingOptionsException', $e);
        }
    }

    // Test specific methods:
}
