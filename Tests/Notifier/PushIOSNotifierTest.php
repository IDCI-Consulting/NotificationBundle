<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Notifier;

use Doctrine\ORM\EntityManager;
use IDCI\Bundle\NotificationBundle\Notifier\PushIOSNotifier;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

class PushIOSNotifierTest extends \PHPUnit_Framework_TestCase
{
    private $notifier;

    public function setUp()
    {
        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->notifier = new PushIOSNotifier($entityManager);
        $this->notifier->setDefaultConfiguration(array());
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
            'deviceToken',
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
            'certificate',
            'passphrase',
            'useSandbox',
        );

        $configureKeys = array_keys($this->notifier->getFromFields());

        asort($expectingKeys);
        asort($configureKeys);

        $this->assertEquals($expectingKeys, $configureKeys);
    }

    public function testCleanData()
    {
    }
/*
    public function testCleanDataWithValidData()
    {
        $entityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $optionResolver = $this->getMockBuilder('\Symfony\Component\OptionsResolver\OptionsResolver')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $data = array(
            'to' => array(
                'deviceToken' => 'abcd1234',
            ),
            'from' => array(
                'certificate' => '/path/to/the/certificate',
                'passphrase' => 'passphrase',
                'useSandbox' => false,
            ),
            'content' => array(
                'message' => 'test',
            ),
        );

        $pushIOSNotifier = new PushIOSNotifier($entityManager, array());
        $this->assertEquals(
            $data,
            $pushIOSNotifier->cleanData($data)
        );
    }

    public function testCleanDataWithInvalidData()
    {
        $entityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $optionResolver = $this->getMockBuilder('\Symfony\Component\OptionsResolver\OptionsResolver')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $data = array(
            'to' => array(
                //"deviceToken" => "abcd1234" //Simulate a mission required field
            ),
            'from' => array(
                'certificate' => '/path/to/the/certificate',
                'passphrase' => 'passphrase',
                'useSandbox' => false,
            ),
            'content' => array(
                'message' => 'test',
            ),
        );

        $pushIOSNotifier = new PushIOSNotifier($entityManager, array());
        $data = $pushIOSNotifier->cleanData($data);
    }
*/
}
