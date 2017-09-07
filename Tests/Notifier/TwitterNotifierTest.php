<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Notifier;

use Doctrine\ORM\EntityManager;
use Da\ApiClientBundle\Http\Rest\RestApiClientInterface;
use IDCI\Bundle\NotificationBundle\Notifier\TwitterNotifier;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

class TwitterNotifierTest extends \PHPUnit_Framework_TestCase
{
    private $notifier;

    public function setUp()
    {
        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $restApiClient = $this->getMockBuilder(RestApiClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->notifier = new TwitterNotifier($entityManager, array(), $restApiClient);
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
        $this->assertFalse($this->notifier->getToFields());
    }

    public function testGetContentFields()
    {
        $expectingKeys = array(
            'status',
        );

        $configureKeys = array_keys($this->notifier->getContentFields());

        asort($expectingKeys);
        asort($configureKeys);

        $this->assertEquals($expectingKeys, $configureKeys);
    }

    public function testGetFromFields()
    {
        $expectingKeys = array(
            'consumerKey',
            'consumerSecret',
            'oauthAccessToken',
            'oauthAccessTokenSecret'
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
        $apiClient = $this->getMockBuilder('\Da\ApiClientBundle\Http\Rest\RestApiClientInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $data = array(
            'from' => array(
                'consumerKey' => 'abcd1234',
                'consumerSecret' => 'efgh56789',
                'oauthAccessToken' => 'ihjk1234',
                'oauthAccessTokenSecret' => 'lmjn56789',
            ),
            'content' => array(
                'status' => 'test',
            ),
        );

        $twitterNotifier = new TwitterNotifier($entityManager, array(), $apiClient);
        $this->assertEquals(
            $data,
            $twitterNotifier->cleanData($data)
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
        $apiClient = $this->getMockBuilder('\Da\ApiClientBundle\Http\Rest\RestApiClientInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $data = array(
            'from' => array(
                'consumerKey' => 'abcd1234',
                'consumerSecret' => 'efgh56789',
                'oauthAccessToken' => 'ihjk1234',
                'oauthAccessTokenSecret' => 'lmjn56789',
            ),
            'content' => array(
                //"status" => "test" //Simulate a missing required field.
            ),
        );

        $twitterNotifier = new TwitterNotifier($entityManager, array(), $apiClient);
        $data = $twitterNotifier->cleanData($data);
    }
*/
}
