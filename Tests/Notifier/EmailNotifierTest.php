<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Notifier;

use Doctrine\ORM\EntityManager;
use IDCI\Bundle\NotificationBundle\Notifier\EmailNotifier;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

class EmailNotifierTest extends \PHPUnit_Framework_TestCase
{
    private $notifier;
    private $notification;

    public function setUp()
    {
        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->notifier = new EmailNotifier($entityManager, array(
            'tracking_url' => 'http://dummy_url'
        ));

        $this->notification = new Notification();
        $this->notification
            ->setType('email')
            ->setFrom(json_encode(array(
                'transport' => 'smtp',
                "from" => "dummy@email.com",
                "fromName" => "dummy@email.com",
                "replyTo" => "dummy@email.com",
                "server" => "server.smtp.fr",
                "login" => "id_value",
                "password" => "password",
                "port" => 123,
                "encryption" => "ssl",
                "track" => true,
            )))
            ->setTo(json_encode(array(
                'to' => 'test@mail.com',
                'cc' => null,
                'bcc' => null,
            )))
            ->setContent(json_encode(array(
                'subject' => 'Test',
                'message' => 'Test message',
                'htmlMessage' => null,
                'attachments' => null,
            )))
        ;
    }

    public function testBuildMessage()
    {
        $configuration = $this->notifier->getConfiguration($this->notification);
        $to = json_decode($this->notification->getTo(), true);
        $content = json_decode($this->notification->getContent(), true);

        $message = \Swift_Message::newInstance()
            ->setSubject(isset($content['subject']) ? $content['subject'] : null)
            ->setFrom(array($configuration['from'] => $configuration['fromName']))
            ->setReplyTo(isset($configuration['replyTo']) ? $configuration['replyTo'] : null)
            ->setTo($to['to'])
            ->setCc(isset($to['cc']) ? $to['cc'] : null)
            ->setBcc(isset($to['bcc']) ? $to['bcc'] : null)
            ->setBody(isset($content['message']) ? $content['message'] : null)
        ;

        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals($message->getSubject(), $this->notifier->buildMessage($this->notification)->getSubject());
    }

    public function testBuildHTMLContent()
    {
        $this->assertEquals(
            '<img alt="picto" src="http://dummy_url?notification_id=&action=open" width="1" height="1" border="0" />',
            $this->notifier->buildHTMLContent($this->notification)
        );
    }

    public function testAddTracker()
    {
        $this->assertEquals(
            '<img alt="picto" src="http://dummy_url?notification_id=&action=open" width="1" height="1" border="0" />',
            $this->notifier->addTracker($this->notification)
        );

        $this->notification->setFrom(json_encode(array(
            'transport' => 'smtp',
            "from" => "dummy@email.com",
            "fromName" => "dummy@email.com",
            "replyTo" => "dummy@email.com",
            "server" => "server.smtp.fr",
            "login" => "id_value",
            "password" => "password",
            "port" => 123,
            "encryption" => "ssl",
            "track" => false,
        )));

        $this->assertEquals('', $this->notifier->addTracker($this->notification));
    }

    public function testCleanDataWithValidData()
    {
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

        $this->assertEquals(
            $data,
            $this->notifier->cleanData($data)
        );
    }

    /**
     * @expectedException Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function testCleanDataWithInvalidData()
    {
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

        $data = $this->notifier->cleanData($data);
    }
}
