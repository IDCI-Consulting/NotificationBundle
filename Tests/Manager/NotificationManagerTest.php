<?php

/**
 * @author:  Remy MENCE <remy.mence@gmail.com>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ORM\EntityManager;
use IDCI\Bundle\NotificationBundle\Manager\NotificationManager;
use IDCI\Bundle\NotificationBundle\Notifier\EmailNotifier;
use IDCI\Bundle\NotificationBundle\Exception\UndefinedNotifierException;
use IDCI\Bundle\NotificationBundle\Exception\NotificationParametersException;

class NotificationManagerTest extends \PHPUnit_Framework_TestCase
{
    private $notificationManager;

    public function setUp()
    {
        $objectManager = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->notificationManager = new NotificationManager($objectManager, $eventDispatcher, null);
    }

    public function testGetNotifier()
    {
        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $notifier1 = new EmailNotifier($entityManager);
        $notifier1->setDefaultConfiguration(array('a' => 'b'));
        $notifier2 = new EmailNotifier($entityManager);
        $notifier2->setDefaultConfiguration(array('c' => 'd'));
        $notifier3 = new EmailNotifier($entityManager);
        $notifier3->setDefaultConfiguration(array('e' => 'f'));

        $this->notificationManager
            ->addNotifier($notifier1, 'notifier1')
            ->addNotifier($notifier2, 'notifier2')
            ->addNotifier($notifier3, 'notifier3')
        ;

        $this->assertEquals($notifier1, $this->notificationManager->getNotifier('notifier1'));
        $this->assertEquals($notifier2, $this->notificationManager->getNotifier('notifier2'));
        $this->assertEquals($notifier3, $this->notificationManager->getNotifier('notifier3'));

        $this->notificationManager
            ->addNotifier($notifier3, 'notifier1')
        ;

        $this->assertEquals($notifier3, $this->notificationManager->getNotifier('notifier1'));
        $this->assertNotEquals($notifier1, $this->notificationManager->getNotifier('notifier1'));

        $this->expectException('IDCI\Bundle\NotificationBundle\Exception\UndefinedNotifierException');
        $this->notificationManager->getNotifier('dummy');
    }

    public function testAddNotification()
    {
        try {
            $this->notificationManager->addNotification('dummy', '', array());
            $this->fail("Expected exception not thrown");
        } catch (UndefinedNotifierException $e) {
            $this->assertEquals("Undefined notifier 'dummy'", $e->getMessage());
        }

        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $emailNotifier = new EmailNotifier($entityManager);
        $this->notificationManager->addNotifier($emailNotifier, 'email');

        try {
            $this->notificationManager->addNotification('email', '{wrong json}', array());
            $this->fail("Expected exception not thrown");
        } catch (NotificationParametersException $e) {
            $this->assertEquals(
                "Notification parameters error : Json decode failed with the given data: {wrong json}",
                $e->getMessage()
            );
        }

        try {
            $this->notificationManager->addNotification('email', '{"a": "b"}', array(1));
            $this->fail("Expected exception not thrown");
        } catch (NotificationParametersException $e) {
            $this->assertEquals(
                "Notification parameters error : The parameters 'idci_notification.files_directory' is not configured",
                $e->getMessage()
            );
        }
    }
}
