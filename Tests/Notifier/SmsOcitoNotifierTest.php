<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Notifier;

use IDCI\Bundle\NotificationBundle\Notifier\SmsOcitoNotifier;

class SmsOcitoNotifierTest extends \PHPUnit_Framework_TestCase
{
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
}
