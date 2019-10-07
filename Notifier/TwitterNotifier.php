<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Entity\Notification;
use Doctrine\ORM\EntityManager;
use Da\ApiClientBundle\Http\Rest\RestApiClientInterface;
use Symfony\Component\Form\Extension\Core\Type as Types;

class TwitterNotifier extends AbstractNotifier
{
    protected $apiClient;

    /**
     * Constructor.
     *
     * @param EntityManager          $entityManager
     * @param RestApiClientInterface $apiClient
     */
    public function __construct(EntityManager $entityManager, RestApiClientInterface $apiClient)
    {
        parent::__construct($entityManager);
        $this->apiClient = $apiClient;
    }

    /**
     * Get ApiClient.
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
        $path = '/statuses/update.json';
        $response = $this->getApiClient()->post(
            $path,
            $notification->getContentDecoded(),
            $this->buildHeaders($path, 'POST', $notification)
        );

        return (null !== $response) ? true : false;
    }

    /**
     * Build headers.
     *
     * @param string       $path
     * @param string       $requestMethod
     * @param Notification $notification
     *
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
     * Build an Oauth object.
     *
     * @param string $path
     * @param string $requestMethod
     * @param array  $configuration
     *
     * @return array $oauth
     */
    protected function buildOauth($path, $requestMethod, $configuration)
    {
        $oauth = array(
            'oauth_consumer_key' => $configuration['consumerKey'],
            'oauth_nonce' => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token' => $configuration['oauthAccessToken'],
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0',
        );

        $baseStringCurl = $this->buildBaseStringCurl(
            $path,
            $requestMethod,
            $oauth
        );

        $compositeKey = rawurlencode($configuration['consumerSecret']).'&'.rawurlencode($configuration['oauthAccessTokenSecret']);
        $oauthSignature = base64_encode(hash_hmac('sha1', $baseStringCurl, $compositeKey, true));
        $oauth['oauth_signature'] = $oauthSignature;

        return $oauth;
    }

    /**
     * Build the base string in order to generate oauth signature.
     *
     * @param string $path
     * @param string $requestMethod
     * @param array  $oauth
     *
     * @return string
     */
    protected function buildBaseStringCurl($path, $requestMethod, $oauth)
    {
        $absolutePath = $this->buildAbsolutePath($path);
        $baseStringValues = array();
        ksort($oauth);

        foreach ($oauth as $key => $value) {
            $baseStringValues[] = $key.'='.$value;
        }

        return $requestMethod.'&'.rawurlencode($absolutePath).'&'.rawurlencode(implode('&', $baseStringValues));
    }

    /**
     * Build absolute path.
     *
     * @param string $path
     *
     * @return string
     */
    protected function buildAbsolutePath($path)
    {
        $endPointRoot = $this->getApiClient()->getEndpointRoot();

        return $endPointRoot.$path;
    }

    /**
     * Build the authorization header.
     *
     * @param array $oauth
     *
     * @return string $authorizationHeader
     */
    protected function buildAuthorizationHeader($oauth)
    {
        $authorizationHeader = 'OAuth ';
        $values = array();

        foreach ($oauth as $key => $value) {
            $values[] = $key.'="'.rawurlencode($value).'"';
        }

        $authorizationHeader .= implode(',', $values);

        return $authorizationHeader;
    }

    /**
     * {@inheritdoc}
     */
    public function getToFields()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getContentFields()
    {
        return array(
            'status' => array(Types\TextareaType::class, array('required' => true)),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return array(
            'consumerKey' => array(Types\TextType::class, array('required' => false)),
            'consumerSecret' => array(Types\TextType::class, array('required' => false)),
            'oauthAccessToken' => array(Types\TextType::class, array('required' => false)),
            'oauthAccessTokenSecret' => array(Types\TextType::class, array('required' => false)),
        );
    }
}
