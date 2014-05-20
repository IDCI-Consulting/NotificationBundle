<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Notifier;

use IDCI\Bundle\NotificationBundle\Notifier\EmailNotifier;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

class EmailNotifierTest extends \PHPUnit_Framework_TestCase
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
                "to"  => "test@mail.com",
                "cc"  => null,
                "bcc" => null
            ),
            'from' => array(
                "transport"  => "smtp",
                "from"       => "from_value",
                "fromName"   => "from_name_value",
                "replyTo"    => "reply_to_value",
                "server"     => "server.smtp.fr",
                "login"      => "id_value",
                "password"   => "password",
                "port"       => 123,
                "encryption" => "ssl"
            ),
            'content' => array(
                "subject"     => "Test",
                "message"     => "Test message",
                "htmlMessage" => null,
                "attachments" => null
            )
        );

        $emailNotifier = new EmailNotifier($entityManager, array());
        $this->assertEquals(
            $data,
            $emailNotifier->cleanData($data)
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
            'to' => array(
                "to"  => "test@mail.com",
                "cc"  => null,
                "bcc" => null
            ),
            'from' => array(
                "transport"  => "smtp",
                "from"       => "from_value",
                "fromName"   => "from_name_value",
                "replyTo"    => "reply_to_value",
                "server"     => "server.smtp.fr",
                "login"      => "id_value",
                "password"   => "password",
                "port"       => 123,
                "encryption" => "ssl"
            ),
            'content' => array(
                //"subject"     => "Test", //Simulate a missing required field.
                "message"     => "Test message",
                "htmlMessage" => null,
                "attachments" => null
            )
        );

        $emailNotifier = new EmailNotifier($entityManager, array());
        $data = $emailNotifier->cleanData($data);
    }
}