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
use IDCI\Bundle\NotificationBundle\Exception\IOSPushNotifierException;

class IOSPushNotifier extends AbstractNotifier
{
    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        $socket = self::createSocketConnexion($notification->getFrom('passphrase'));

        return self::sendBinaryMessage(
            $socket,
            $this->buildBinaryMessage(
                $notification->getTo('deviceToken'),
                $notification->getContent('message')
            )
        );
    }

    /**
     * Init the socket connexion
     *
     * @param string $passphrase
     * @thrown SocketConnexionFailedException
     */
    public static function createSocketConnexion($passphrase)
    {
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', '/home/quentin/workspace/NotificationManager/bin/ck.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        $socket = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195',
            $err,
            $errstr,
            60,
            STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT,
            $ctx
        );

        if (!$socket) {
            throw new IOSPushNotifierException(sprintf(
                'Connection failed: %s, %s',
                $err,
                $errstr
            ));
        }

        return $socket;
    }

    /**
     * Send the payload using socket connexion
     *
     * @param  TODO   $socket
     * @param  string $binaryMessage
     * @return boolean
     */
    public static function sendBinaryMessage($socket, $binaryMessage)
    {
        $result = fwrite($socket, $binaryMessage, strlen($binaryMessage));
        fclose($socket);
        //var_dump($result); die;
        return false === $result ? false : true;
    }

    /**
     * Build the binary notification
     *
     * @param string $deviceToken
     * @param string $message
     * @return string
     */
    protected function buildBinaryMessage($deviceToken, $message)
    {
        /*$body['aps'] = array(
            'alert' => $message,
            'sound' => 'default'
        );*/
        $payload = json_encode(array(
            'aps' => array(
                'alert' => $message,
                'sound' => 'default'
            )
        ));

        return chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
    }

    /**
     * {@inheritdoc}
     */
    public function getToFields()
    {
        return array(
            'deviceToken' => array('text', array('required' => true))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return array(
            'passphrase' => array('password', array('required' => true))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getContentFields()
    {
        //256 charaters max (38 characters used for others fields)
        return array(
            'message' => array('text', array('required' => true, 'max_length' => 218))
        );
    }
}