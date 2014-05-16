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
use IDCI\Bundle\NotificationBundle\Exception\PushAndroidNotifierException;

class PushAndroidNotifier extends AbstractNotifier
{
    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        $configuration = $this->getConfiguration($notification);
        $message = self::buildGcmMessage($notification);

        return self::sendPushAndroid($configuration['apiKey'], $message);
    }

    /**
     * Build the GCM message
     *
     * @param notification $notification
     * @return string
     */
    public static function buildGcmMessage(notification $notification)
    {
        $gcmMessage = array(
            'message'    => $notification->getContent('message'),
            'vibrate'    => 1,
            'sound'      => 1
        );

        $gcmFields = array(
            'delay_while_idle' => true,
            'registration_ids' => array($notification->getTo('deviceToken')),
            'data'             => $gcmMessage
        );

        return json_encode($gcmFields);
    }

    /**
     * Send push android
     *
     * @param string $apiKey
     * @param string $message
     * @return boolean
     */
    public static function sendPushAndroid($apiKey, $message)
    {
        $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        //TO DO later : put url in conf
        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
        $response = curl_exec($ch);
        curl_close($ch);

        $decodedResponse = json_decode($response, true);
        if (null === $decodedResponse) {
            throw new PushAndroidNotifierException(sprintf(
                "Error : \n [Api key]: %s, \n [Response] : \n %s",
                $apiKey,
                $response
            ));
        } elseif ($decodedResponse['failure'] > 0) {
            $decodedMessage = json_decode($message, true);
            throw new PushAndroidNotifierException(sprintf(
                "Push notification not sent : [Error] : %s, \n [Api key] : %s, \n [Device token] : %s",
                $decodedResponse['results'][0]['error'],
                $apiKey,
                json_encode($decodedMessage['registration_ids'])
            ));
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return array('apiKey' => array('text', array('required' => false)));
    }

    /**
     * {@inheritdoc}
     */
    public function getToFields()
    {
        return array('deviceToken' => array('text', array('required' => true)));
    }

    /**
     * {@inheritdoc}
     */
    public function getContentFields()
    {
        return array('message' => array('textarea', array('required' => true)));
    }
}
