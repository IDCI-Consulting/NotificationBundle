<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Notifier;

use IDCI\Bundle\NotificationBundle\Notifier\PushAndroidNotifier;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

class PushAndroidNotifierTest extends \PHPUnit_Framework_TestCase
{
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
                "deviceToken" => "abcd1234"
            ),
            'from' => array(
                "apiKey" => "123456"
            ),
            'content' => array(
                "message" => "test"
            )
        );

        $pushAndroidNotifier = new PushAndroidNotifier($entityManager, array());
        $this->assertEquals(
            $data,
            $pushAndroidNotifier->cleanData($data)
        );
    }

    /**
     * @expectedException Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
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
            // We simulate an missing required field.
            'to' => array(
                //"deviceToken" => "abcd1234"
            ),
            'from' => array(
                "apiKey" => "123456"
            ),
            'content' => array(
                "message" => "test"
            )
        );

        $pushAndroidNotifier = new PushAndroidNotifier($entityManager, array());
        $data = $pushAndroidNotifier->cleanData($data);
    }

    public function testBuildGcmMessage()
    {
        $toData = array("deviceToken" => "abcd1234");
        $contentData = array("message" => "test");
        $pushAndroidNotification = new Notification();
        $pushAndroidNotification
            ->setTo(json_encode($toData))
            ->setContent(json_encode($contentData))
        ;

        $gcmMessage = array(
            'message'    => $contentData['message'],
            'vibrate'    => 1,
            'sound'      => 1
        );

        $gcmFields = array(
            'delay_while_idle' => true,
            'registration_ids' => array($toData['deviceToken']),
            'data'             => $gcmMessage
        );

        $this->assertEquals(
            json_encode($gcmFields),
            PushAndroidNotifier::buildGcmMessage($pushAndroidNotification)
        );

    }
}