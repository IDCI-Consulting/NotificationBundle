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

    public function setUp()
    {
        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->notifier = new FacebookNotifier($entityManager, array());
    }

    // Test NotifierInterface methods:

    public function testSendNotification()
    {
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
    }
}
