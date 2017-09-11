<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Notifier;

use Doctrine\ORM\EntityManager;
use IDCI\Bundle\NotificationBundle\Notifier\MailNotifier;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

class MailNotifierTest extends \PHPUnit_Framework_TestCase
{
    private $notifier;

    public function setUp()
    {
        $entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->notifier = new MailNotifier($entityManager);
        $this->notifier->setDefaultConfiguration(array());
    }

    // Test NotifierInterface methods:

    public function testSendNotification()
    {
        // TODO
    }

    public function testGetToFields()
    {
        $expectingKeys = array(
            'firstName',
            'lastName',
            'address',
            'postalCode',
            'city',
            'country',
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
            'firstName',
            'lastName',
            'address',
            'postalCode',
            'city',
            'country',
        );

        $configureKeys = array_keys($this->notifier->getFromFields());

        asort($expectingKeys);
        asort($configureKeys);

        $this->assertEquals($expectingKeys, $configureKeys);
    }

    public function testCleanData()
    {
        $data = array(
            'to' => array(
                'firstName' => 'to_firstName',
                'lastName' => 'to_lastName',
                'address' => 'to_address',
                'postalCode' => 'to_postalCode',
                'city' => 'to_city',
                'country' => 'to_country',
            ),
            'from' => array(
                'firstName' => 'from_firstName',
                'lastName' => 'from_lastName',
                'address' => 'from_address',
                'postalCode' => 'from_postalCode',
                'city' => 'from_city',
                'country' => 'from_country',
            ),
            'content' => array(
                'message' => 'Test message',
            ),
        );

        $this->assertEquals(
            $data,
            $this->notifier->cleanData($data)
        );

        // Missing to:city
        $data = array(
            'to' => array(
                'firstName' => 'to_firstName',
                'lastName' => 'to_lastName',
                'address' => 'to_address',
                'postalCode' => 'to_postalCode',
                'country' => 'to_country',
            ),
            'from' => array(
                'firstName' => 'from_firstName',
                'lastName' => 'from_lastName',
                'address' => 'from_address',
                'postalCode' => 'from_postalCode',
                'city' => 'from_city',
                'country' => 'from_country',
            ),
            'content' => array(
                'message' => 'Test message',
            ),
        );

        try {
            $data = $this->notifier->cleanData($data);
            $this->fail("Expected exception not thrown");
        } catch(\Exception $e) {
            $this->assertInstanceOf('\Symfony\Component\OptionsResolver\Exception\MissingOptionsException', $e);
        }
    }

    // Test specific methods:
}
