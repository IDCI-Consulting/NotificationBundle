<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Notifier;

use IDCI\Bundle\NotificationBundle\Notifier\EmailNotifier;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

class AbstractNotifierTest extends \PHPUnit_Framework_TestCase
{
    public function testCleanEmptyValue()
    {

    }

    public function getNotificationWithNotifierParameters()
    {
        /*
        $fromData = array(
            'transport' => 'smtp',
            'from' => 'from_value',
            'fromName' => 'from_name_value',
            'replyTo' => 'reply_to_value',
            'server' => 'server.smtp.fr',
            'login' => 'id_value',
            'password' => 'password',
            'port' => 123,
            'encryption' => 'ssl',
        );

        $emailNotificationWithNotifierParameters = new Notification();
        $emailNotificationWithNotifierParameters
            ->setType('email')
            ->setFrom(json_encode($fromData))
        ;

        return array(array($emailNotificationWithNotifierParameters));
        */
    }

    public function getNotificationWithNotifierAlias()
    {
        /*
        $fromData = array(
            'transport' => 'smtp',
            'from' => 'from_value',
            'fromName' => 'from_name_value',
            'replyTo' => 'reply_to_value',
            'server' => 'server.smtp.fr',
            'login' => 'id_value',
            'password' => 'password',
            'port' => 123,
            'encryption' => 'ssl',
        );

        $emailNotificationWithAlias = new Notification();
        $emailNotificationWithAlias
            ->setType('email')
            ->setNotifierAlias('notifier_alias_value')
        ;

        $emailNotificationWithAllFields = new Notification();
        $emailNotificationWithAllFields
            ->setType('email')
            ->setNotifierAlias('notifier_alias_value')
            ->setFrom(json_encode($fromData))
        ;

        return array(
            array($emailNotificationWithAlias),
            array($emailNotificationWithAllFields),
        );
        */
    }

    public function getNotificationUsingDefaultConfiguration()
    {
        //return array(array(new Notification()));
    }

    /**
     * @dataProvider getNotificationWithNotifierParameters
     */
    public function testGetConfigurationWithNotifierParameters(Notification $notification)
    {
        /*
        $entityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $defaultConfiguration = array();
        $emailNotifier = new EmailNotifier($entityManager, $defaultConfiguration);

        $this->assertEquals(
            $notification->getFromDecoded(),
            $emailNotifier->getConfiguration($notification)
        );
        */
    }

    /**
     * @dataProvider getNotificationWithNotifierAlias
     */
    public function testGetConfigurationWithNotifierAlias(Notification $notification)
    {
        /*
        $configurationData = array(
            'notifier_alias_value' => array(
                'transport' => 'smtp',
                'from' => 'from_value',
                'fromName' => 'from_name_value',
                'replyTo' => 'reply_to_value',
                'server' => 'smtp.mail.com',
                'login' => 'login_value',
                'password' => 'password',
                'port' => 123,
                'encryption' => 'ssl',
            ),
        );

        $notifierConfiguration = $this
            ->getMockBuilder('\IDCI\Bundle\NotificationBundle\Entity\NotifierConfiguration')
            ->getMock()
        ;

        $notifierConfiguration
            ->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue(json_encode($configurationData['notifier_alias_value'])))
        ;

        $notifierConfigurationRepository = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $notifierConfigurationRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($notifierConfiguration))
        ;

        $entityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($notifierConfigurationRepository))
        ;

        $defaultConfiguration = array();
        $emailNotifier = new EmailNotifier($entityManager, $defaultConfiguration);

        $this->assertEquals(
            $configurationData['notifier_alias_value'],
            $emailNotifier->getConfiguration($notification)
        );
        */
    }

    /**
     * @dataProvider getNotificationUsingDefaultConfiguration
     */
    public function testGetConfigurationFromFileConfiguration(Notification $notification)
    {
        /*
        $entityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $defaultConfiguration = array(
            'default_configuration' => 'default',
            'configurations' => array(
                'default' => array(
                    'transport' => 'smtp',
                    'fromName' => 'from_name_value',
                    'from' => 'from_value',
                    'server' => 'smtp.mail.com',
                    'login' => 'login_value',
                    'password' => 'password',
                    'port' => 123,
                    'encryption' => 'ssl',
                ),
            ),
        );

        $emailNotifier = new EmailNotifier($entityManager, $defaultConfiguration);
        $this->assertEquals(
            $defaultConfiguration['configurations']['default'],
            $emailNotifier->getConfiguration($notification)
        );
        */
    }
}
