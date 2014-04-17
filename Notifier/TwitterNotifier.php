<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Entity\Notification;
use Doctrine\ORM\EntityManager;
use Da\ApiClientBundle\Http\Rest\RestApiClientInterface;
use IDCI\Bundle\NotificationBundle\Exception\NotificationFieldParseErrorException;
use IDCI\Bundle\NotificationBundle\Exception\UndefindedArgumentException;

class TwitterNotifier extends AbstractNotifier
{
    protected $apiClient;

    /**
     * Constructor
     *
     * @param EntityManager          $entityManager
     * @param array                  $defaultConfiguration
     * @param RestApiClientInterface $apiClient
     */
    public function __construct(EntityManager $entityManager, $defaultConfiguration, RestApiClientInterface $apiClient)
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
        $path = '/1.1/statuses/update.json';

        $this->getApiClient()->post(
            $path,
            $this->getDataContentField($notification),
            $this->buildHeaders($path, "POST", $notification)
        );
    }

    /**
     * Get data from content field
     *
     * @param Notification $notification
     * @return array
     */
    protected function getDataContentField(Notification $notification)
    {
        if (null === json_decode($notification->getContent(), true)) {
            throw new NotificationFieldParseErrorException($notification->getContent());
        }
        $contentValue = json_decode($notification->getContent(), true);

        return $contentValue;
    }

    /**
     * Build headers
     *
     * @param string       $path
     * @param string       $requestMethod
     * @param Notification $notification
     * @return array
     */
    protected function buildHeaders($path, $requestMethod, Notification $notification)
    {
        $headers = array();
        $configuration = $this->getConfiguration($notification);
        $oauth = $this->buildOauth($path, $requestMethod, $configuration);
        $headers['Authorization'] = $this->buildAuthorizationHeader($oauth);

        return $headers;
    }

    /**
     * Build an Oauth object
     *
     * @param string $path
     * @param string $requestMethod
     * @param array  $notification
     * @return array $oauth
     */
    protected function buildOauth($path, $requestMethod, $configuration)
    {
        $oauth = array(
            'oauth_consumer_key'     => $configuration['consumer_key'],
            'oauth_nonce'            => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token'            => $configuration['oauth_access_token'],
            'oauth_timestamp'        => time(),
            'oauth_version'          => '1.0'
        );

        $baseStringCurl = $this->buildBaseStringCurl(
            $path,
            $requestMethod,
            $oauth
        );

        $compositeKey = rawurlencode($configuration['consumer_secret']). '&' . rawurlencode($configuration['oauth_access_token_secret']);
        $oauthSignature = base64_encode(hash_hmac('sha1', $baseStringCurl, $compositeKey, true));
        $oauth['oauth_signature'] = $oauthSignature;

        return $oauth;
    }

    /**
     * Build the base string in order to generate oauth signature
     *
     * @param string $path
     * @param string $requestMethod
     * @param array  $oauth
     * @return string
     */
    protected function buildBaseStringCurl($path, $requestMethod, $oauth)
    {
        $absolutePath = $this->buildAbsolutePath($path);
        $baseStringValues = array();
        ksort($oauth);

        foreach($oauth as $key => $value) {
            $baseStringValues[] = $key. "=" . $value;
        }

        return $requestMethod . '&' . rawurlencode($absolutePath) . '&' . rawurlencode(implode('&', $baseStringValues));
    }

    /**
     * Build absolute path
     *
     * @param string $path
     * @return string
     */
    protected function buildAbsolutePath($path)
    {
        $endPointRoot = $this->getApiClient()->getEndpointRoot();

        return $endPointRoot . $path;
    }

    /**
     * Build the authorization header
     *
     * @param  array  $oauth
     * @return string $authorizationHeader
     */
    protected function buildAuthorizationHeader($oauth)
    {
        $authorizationHeader = 'OAuth ';
        $values = array();

        foreach ($oauth as $key => $value) {
            $values[] = $key . "=\"" . rawurlencode($value) . "\"";
        }

        $authorizationHeader .= implode(',', $values);

        return $authorizationHeader;
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return array(
            'consumer_key'              => array('text', array('required' => false)),
            'consumer_secret'           => array('text', array('required' => false)),
            'oauth_access_token'        => array('text', array('required' => false)),
            'oauth_access_token_secret' => array('text', array('required' => false))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getContentFields()
    {
        return array(
            'status' => array('textarea', array('required' => true))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getToFields()
    {
        return false;
    }
}
