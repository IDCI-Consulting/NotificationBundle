<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Notifier;

use Doctrine\ORM\EntityManager;
use IDCI\Bundle\NotificationBundle\Notifier\PushAndroidNotifier;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

class PushAndroidNotifierTest extends \PHPUnit_Framework_TestCase
{
    private $notifier;
    private $notification;

    public function setUp()
    {
        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->notifier = new PushAndroidNotifier($entityManager, array());

        $this->notification = new Notification();
        $this->notification
            ->setType('push_android')
            ->setFrom(json_encode(array('apiKey' => 'http://dummy_url')))
            ->setTo(json_encode(array('deviceToken' => 'abcd1234')))
            ->setContent(json_encode(array('message' => 'test')))
        ;
    }

    public function testCleanDataWithValidData()
    {
        $data = array(
            'to' => array(
                'deviceToken' => 'abcd1234',
            ),
            'from' => array(
                'apiKey' => '123456',
            ),
            'content' => array(
                'message' => 'test',
            ),
        );

        $this->assertEquals(
            $data,
            $this->notifier->cleanData($data)
        );
    }

    /**
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function testCleanDataWithInvalidData()
    {
        $data = array(
            // We simulate an missing required field.
            'to' => array(
                //"deviceToken" => "abcd1234"
            ),
            'from' => array(
                'apiKey' => '123456',
            ),
            'content' => array(
                'message' => 'test',
            ),
        );

        $data = $this->notifier->cleanData($data);
    }

    public function testBuildGcmMessage()
    {
        $gcmMessage = array(
            'message' => 'test',
            'vibrate' => 1,
            'sound' => 1,
        );

        $gcmFields = array(
            'delay_while_idle' => true,
            'registration_ids' => array('abcd1234'),
            'data' => $gcmMessage,
        );

        $this->assertEquals(
            json_encode($gcmFields),
            $this->notifier->buildGcmMessage($this->notification)
        );
    }

    /**
     * @expectedException \IDCI\Bundle\NotificationBundle\Exception\PushAndroidNotifierException
     */
    public function testPushAndroidNotifierException()
    {
        $this->notifier->sendPushAndroid($this->notification->getFrom(), $this->notification->getContent());
    }
}
