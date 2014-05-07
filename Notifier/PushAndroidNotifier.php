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

class PushAndroidNotifier extends AbstractNotifier
{
    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        $configuration = $this->getConfiguration($notification);
        $headers = array(
            'Authorization: key=' . $configuration['apiKey'],
            'Content-Type: application/json'
        );
        $message = self::buildGcmMessage($notification);
        $response = json_decode(self::sendPushAndroid($headers, $message), true);

        return ($response['success'] > 0) ? true : false;
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
            'registration_ids' => array($notification->getTo('deviceToken')),
            'data'             => $gcmMessage
        );

        return json_encode($gcmFields);
    }

    /**
     * Send push android
     *
     * @param array  $headers
     * @param string $message
     * @return string
     */
    public static function sendPushAndroid($headers, $message)
    {
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

        return $response;
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
        return array('message' => array('textarea', array('required' => false)));
    }
}
