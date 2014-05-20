<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Tests\Notifier;

use IDCI\Bundle\NotificationBundle\Notifier\TwitterNotifier;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

class TwitterNotifierTest extends \PHPUnit_Framework_TestCase
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
        $apiClient = $this->getMockBuilder('\Da\ApiClientBundle\Http\Rest\RestApiClientInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $data = array(
            'from' => array(
                'consumerKey'            => 'abcd1234',
                'consumerSecret'         => 'efgh56789',
                'oauthAccessToken'       => 'ihjk1234',
                'oauthAccessTokenSecret' => 'lmjn56789'
            ),
            'content' => array(
                "status" => "test"
            )
        );

        $twitterNotifier = new TwitterNotifier($entityManager, array(), $apiClient);
        $this->assertEquals(
            $data,
            $twitterNotifier->cleanData($data)
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
        $apiClient = $this->getMockBuilder('\Da\ApiClientBundle\Http\Rest\RestApiClientInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $data = array(
            'from' => array(
                'consumerKey'            => 'abcd1234',
                'consumerSecret'         => 'efgh56789',
                'oauthAccessToken'       => 'ihjk1234',
                'oauthAccessTokenSecret' => 'lmjn56789'
            ),
            'content' => array(
                //"status" => "test" //Simulate a missing required field.
            )
        );

        $twitterNotifier = new TwitterNotifier($entityManager, array(), $apiClient);
        $data = $twitterNotifier->cleanData($data);
    }
}