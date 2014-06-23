<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Entity\Notification;
use Doctrine\ORM\EntityManager;
use Da\ApiClientBundle\Http\Rest\RestApiClientInterface;
use IDCI\Bundle\NotificationBundle\Exception\SmsOcitoNotifierException;

class SmsOcitoNotifier extends AbstractNotifier
{
    protected $apiClient;

    /**
     * Constructor
     *
     * @param EntityManager          $entityManager
     * @param array                  $defaultConfiguration
     * @param RestApiClientInterface $apiClient
     */
    public function __construct(
        EntityManager $entityManager,
        $defaultConfiguration,
        RestApiClientInterface $apiClient
    )
    {
        $this->entityManager = $entityManager;
        $this->defaultConfiguration = $defaultConfiguration;
        $this->apiClient = $apiClient;
    }

    /**
     * Get ApiClient
     *
     * @return RestApiClientInterface
     */
    protected function getApiClient()
    {
        return $this->apiClient;
    }

    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        $response = $this->getApiClient()->get(
            '/connecteur/mt/SendUtf8MTRequest.jsp',
            $this->buildQueryStringParameters($notification)
        );

        if(!ereg("Status=0", $response)) {
            throw new SmsOcitoNotifierException(sprintf(
                "[FAILED] : %s",
                strip_tags($response)
            ));
        } else {
            $notification->addLog(sprintf(
                "[SUCCESS] : %s",
                strip_tags($response)

            ));
        }
    }

    /**
     * GetQueryStringParameters
     *
     * @param Notification $notification
     * @return array
     */
    protected function buildQueryStringParameters(Notification $notification)
    {
        $configuration = $this->getConfiguration($notification);

        $queryStringParameters = array(
            'UserName'    => $configuration['userName'],
            'Password'    => $configuration['password'],
            'Content'     => $notification->getContent('message'),
            'DA'          => $notification->getTo('phoneNumber'),
            'Flags'       => $configuration['flag'],
            'SenderAppId' => $configuration['senderAppId'],
            'SOA' => isset($configuration['senderId'])
                ? $configuration['senderId']
                : null,
            'Priority' => isset($configuration['priority'])
                ? $configuration['priority']
                : null,
            'TimeToLive' => isset($configuration['timeToLiveTimeout'])
                ? self::getFuturTime($configuration['timeToLiveTimeout'])
                : null,
            'TimeToSend' => isset($configuration['timeToSendTimeout'])
                ? self::getFuturTime($configuration['timeToSendTimeout'])
                : null
        );

        return self::cleanQueryString($queryStringParameters);
    }

    /**
     * CleanArray
     *
     * @param  array $queryStringParameters
     * @return array $queryStringParameters
     */
    public static function cleanQueryString(array $queryStringParameters)
    {
        foreach ($queryStringParameters as $key => $value) {
            if (null === $value) {
                unset($queryStringParameters[$key]);
            }
        }

        return $queryStringParameters;
    }

    /**
     * GetFuturTime
     *
     * @param integer $timeout
     * @return string
     */
    public static function getFuturTime($timeout)
    {
        $date = new \DateTime('now');
        $date->add(new \DateInterval('PT'.$timeout.'S'));

        return $date->format('Y-m-d H:i:s');
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return array(
            'userName'    => array('text',    array('required' => false, 'max_length' => 30)),
            'password'    => array('text',    array('required' => false, 'max_length' => 30)),
            'senderAppId' => array('text',    array('required' => false, 'max_length' => 10)),
            'senderId'    => array('text',    array('required' => false, 'max_length' => 11)),
            'flag'        => array('integer', array(
                'required'   => false,
                'max_length' => 10,
                'data'       => 3
            )),
            'priority'    => array('choice', array(
                'required' => false,
                'choices' => array(
                    'H' => 'high',
                    'L' => 'low'
                )
            )),
            'timeToLiveTimeout' => array('integer', array('required' => false)),
            'timeToSendTimeout' => array('integer', array('required' => false))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getToFields()
    {
        return array(
            'phoneNumber' => array('text', array('required' => true, 'max_length' => 30))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getContentFields()
    {
        return array(
            'message' => array('text', array('required' => true, 'max_length' => 70))
        );
    }
}
