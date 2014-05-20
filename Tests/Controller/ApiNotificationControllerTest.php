<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiNotificationControllerTest extends WebTestCase
{
    public function testPostNotificationsActionWithInvalidNotifierType()
    {
        $emailNotificationData = array(
            'invalidNotifierType' => array(
                array(
                    "notifierAlias" => "functionnal test",
                    "from" => array(),
                    "to" => array(
                        "to"  => "me@mymail.com",
                        "cc"  => "cc1@mymail.com, cc2@mymail.com",
                        "bcc" => "bcc@mymail.com"
                    ),
                    "content" => array(
                        "subject"     => "notification via command line",
                        "message"     => "the message to be send",
                        "htmlMessage" => "<h1>Titre</h1><p>Message</p>",
                        "attachments" => array()
                    )
                )
            )
        );
        $client = static::createClient();
        $crawler = $client->request(
            'POST',
            '/api/notifications',
            array(
                'invalidNotifierType' => json_encode($emailNotificationData['invalidNotifierType'])
            )
        );
        $this->assertEquals(501, $client->getResponse()->getStatusCode());
    }

    public function testPostNotificationsActionWithValideNotifierType()
    {
        $emailNotificationData = array(
            'email' => array(
                array(
                    "notifierAlias" => "functionnal test",
                    "from" => array(),
                    "to" => array(
                        "to"  => "me@mymail.com",
                        "cc"  => "cc1@mymail.com, cc2@mymail.com",
                        "bcc" => "bcc@mymail.com"
                    ),
                    "content" => array(
                        "subject"     => "notification via command line",
                        "message"     => "the message to be send",
                        "htmlMessage" => "<h1>Titre</h1><p>Message</p>",
                        "attachments" => array()
                    )
                )
            )
        );
        $client = static::createClient();
        $crawler = $client->request(
            'POST',
            '/api/notifications',
            array(
                'email' => json_encode($emailNotificationData['email'])
            )
        );
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testPostNotificationsActionWithBadRequest()
    {
        $client = static::createClient();
        $crawler = $client->request(
            'POST',
            '/api/notifications',
            array(
                //Double quote is missing.
                'email' => '[{notifierAlias:"functionnal test","from":[],"to":{"to":"me@mymail.com"},"content":{"subject":"notification via command line","message":"the message to be send"}}]'
            )
        );
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}