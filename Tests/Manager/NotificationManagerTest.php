<?php

/**
 *
 * @author:  Remy MENCE <remy.mence@gmail.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ORM\EntityManager;

use IDCI\Bundle\NotificationBundle\Notifier\NotifierInterface;
use IDCI\Bundle\NotificationBundle\Manager\NotificationManager;
use IDCI\Bundle\NotificationBundle\Notifier\EmailNotifier;

class NotificationManagerTest extends \PHPUnit_Framework_TestCase
{
    private $notificationManager;
    private $notifier;

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

        $this->notificationManager = new NotificationManager($objectManager, $eventDispatcher);

        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->notifier = new EmailNotifier($entityManager, array('tracking_url' => 'http://dummy_url'));
        $this->notificationManager->addNotifier($this->notifier, 'test');
    }

    public function testGetNotifier()
    {
        $this->assertEquals($this->notifier, $this->notificationManager->getNotifier('test'));

        $this->expectException('IDCI\Bundle\NotificationBundle\Exception\UndefinedNotifierException');
        $this->notificationManager->getNotifier('tes');
    }
}
