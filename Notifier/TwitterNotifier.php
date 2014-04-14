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
    /**
     * Constructor
     *
     * @param EntityManager          $entityManager
     * @param array                  $defaultConfiguration
     * @param RestApiClientInterface $apiClient
     */
    public function __construct(EntityManager $entityManager, $defaultConfiguration)
    {
        $this->entityManager = $entityManager;
        $this->defaultConfiguration = $defaultConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        $path = "https://api.twitter.com/1.1/statuses/update.json";
        $requestMethod = "POST";
        $configuration = $this->getConfiguration($notification);

        $oauth = $this->buildOauth($path, $requestMethod, $configuration);
        $header = array($this->AuthorizationHeaderBuilder($oauth), 'Expect:');
        $postFields = $this->postFieldsBuilder($notification->getContent());
        $this->performRequest($header, $postFields, $path);
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

        $baseStringCurl = $this->baseStringCurlBuilder(
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
     * Build the base string used by cURL
     *
     * @param string $path
     * @param string $requestMethod
     * @param array  $oauth
     * @return string
     */
    protected function baseStringCurlBuilder($path, $requestMethod, $oauth)
    {
        $baseStringValues = array();
        ksort($oauth);

        foreach($oauth as $key => $value) {
            $baseStringValues[] = $key. "=" . $value;
        }

        return $requestMethod . '&' . rawurlencode($path) . '&' . rawurlencode(implode('&', $baseStringValues));
    }

    /**
     * Build the authorization header
     *
     * @param  array  $oauth
     * @return string $authorizationHeader
     */
    protected function AuthorizationHeaderBuilder($oauth)
    {
        $authorizationHeader = 'Authorization: OAuth ';
        $values = array();

        foreach ($oauth as $key => $value) {
            $values[] = $key . "=\"" . rawurlencode($value) . "\"";
        }

        $authorizationHeader .= implode(',', $values);

        return $authorizationHeader;
    }

    /**
     * Build the POST fields
     *
     * @param  string $notificationContentField
     * @return string $contentFieldValues
     */
    protected function postFieldsBuilder($notificationContentField)
    {
        $contentFieldValues = json_decode($notificationContentField, true);
        if (null === $contentFieldValues) {
            throw new NotificationFieldParseErrorException($notificationContentField);
        }

        if (!isset($contentFieldValues['status'])) {
            throw new UndefindedArgumentException('Undefinded "status" subfield in "content" field');
        } elseif ('@' === substr($contentFieldValues['status'], 0,1)) {
            $contentFieldValues['status'] = sprintf("\0%s", $contentFieldValues['status']);
        }

        return $contentFieldValues;
    }

    /**
     * Perform request
     *
     * @param array  $header
     * @param array  $postFields
     * @param string $path
     * @return string
     */
    protected function performRequest($header, $postFields, $path)
    {
        $options = array(
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_HEADER         => false,
            CURLOPT_URL            => $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10
        );

        if (!is_null($postFields)) {
            $options[CURLOPT_POSTFIELDS] = $postFields;
        } else {
            throw new UndefindedArgumentException('Undefinded "post fields" argument.');
        }

        $feed = curl_init();
        curl_setopt_array($feed, $options);
        curl_exec($feed);
        curl_close($feed);
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
