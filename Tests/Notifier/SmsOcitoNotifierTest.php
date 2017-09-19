<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Notifier;

use Doctrine\ORM\EntityManager;
use Da\ApiClientBundle\Http\Rest\RestApiClientInterface;
use IDCI\Bundle\NotificationBundle\Notifier\SmsOcitoNotifier;

class SmsOcitoNotifierTest extends \PHPUnit_Framework_TestCase
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

        $this->notifier = new SmsOcitoNotifier($entityManager, $restApiClient);
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
            'phoneNumber',
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
            'userName',
            'password',
            'senderAppId',
            'senderId',
            'flag',
            'priority',
            'timeToLiveDuration',
            'timeToSendDuration',
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
    public function testCleanQueryString()
    {
        $queryStringParameters = array(
            'UserName' => 'userName_value',
            'Password' => 'password',
            'Content' => 'message to send',
            'DA' => '33664587951',
            'Flags' => 3,
            'SenderAppId' => '1234',
            'SOA' => null,
            'Priority' => null,
            'TimeToLive' => null,
            'TimeToSend' => null,
        );

        $expectedQueryStringParameters = array(
            'UserName' => 'userName_value',
            'Password' => 'password',
            'Content' => 'message to send',
            'DA' => '33664587951',
            'Flags' => 3,
            'SenderAppId' => '1234',
        );

        $this->assertEquals(
            $expectedQueryStringParameters,
            SmsOcitoNotifier::cleanQueryString($queryStringParameters)
        );
    }
*/
}
