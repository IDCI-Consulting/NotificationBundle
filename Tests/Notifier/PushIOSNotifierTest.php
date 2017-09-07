<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Notifier;

use IDCI\Bundle\NotificationBundle\Notifier\PushIOSNotifier;

class PushIOSNotifierTest extends \PHPUnit_Framework_TestCase
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
                'deviceToken' => 'abcd1234',
            ),
            'from' => array(
                'certificate' => '/path/to/the/certificate',
                'passphrase' => 'passphrase',
                'useSandbox' => false,
            ),
            'content' => array(
                'message' => 'test',
            ),
        );

        $pushIOSNotifier = new PushIOSNotifier($entityManager, array());
        $this->assertEquals(
            $data,
            $pushIOSNotifier->cleanData($data)
        );
    }

    /**
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
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
                //"deviceToken" => "abcd1234" //Simulate a mission required field
            ),
            'from' => array(
                'certificate' => '/path/to/the/certificate',
                'passphrase' => 'passphrase',
                'useSandbox' => false,
            ),
            'content' => array(
                'message' => 'test',
            ),
        );

        $pushIOSNotifier = new PushIOSNotifier($entityManager, array());
        $data = $pushIOSNotifier->cleanData($data);
    }
}
