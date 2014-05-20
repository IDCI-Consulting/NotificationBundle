<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Entity;

use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Exception\NotificationFieldParseErrorException;

class NotificationTest extends \PHPUnit_Framework_TestCase
{

    public function getProvidedData()
    {
        $emailNotification = new Notification();
        $emailNotification
            ->setType('email')
            ->setFrom('{"transport":"smtp","from":"from_value","fromName":"from_name_value","replyTo":"reply_to_value","server":"server.smtp.fr","login":"id_value","password":"password","port":123,"encryption":"ssl"}')
            ->setTo('{"to":"test@mail.com","cc":null,"bcc":null}')
            ->setContent('{"subject":"Test","message":"Test message","htmlMessage":null,"attachments":null}')
        ;

        return array(
            array($emailNotification)
        );
    }

    /**
     * @dataProvider getProvidedData
     */
    public function testGetData(Notification $emailNotification)
    {
        $this->assertEquals(
            'from_name_value',
            Notification::getData($emailNotification->getFrom(), 'fromName', null)
        );
    }

    /**
     * @dataProvider getProvidedData
     */
    public function testGetToDecoded(Notification $emailNotification)
    {
        $this->assertEquals(
            json_decode($emailNotification->getTo(), true),
            $emailNotification->getToDecoded()
        );
    }

    /**
     * @dataProvider getProvidedData
     */
    public function testGetFromDecoded(Notification $emailNotification)
    {
        $this->assertEquals(
            json_decode($emailNotification->getFrom(), true),
            $emailNotification->getFromDecoded()
        );
    }

    /**
     * @dataProvider getProvidedData
     */
    public function testGetContentDecoded(Notification $emailNotification)
    {
        $this->assertEquals(
            json_decode($emailNotification->getContent(), true),
            $emailNotification->getContentDecoded()
        );
    }
}