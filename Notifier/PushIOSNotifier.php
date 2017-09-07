<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Exception\PushIOSNotifierException;

class PushIOSNotifier extends AbstractNotifier
{
    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        $configuration = $this->getConfiguration($notification);
        if (null === $configuration['certificate']) {
            throw new PushIOSNotifierException('Empty certificate');
        }

        if (null === $configuration['passphrase']) {
            throw new PushIOSNotifierException('Empty passphrase');
        }

        $socket = self::createSocketConnexion(
            $configuration['useSandbox'],
            $configuration['certificate']['path'],
            $configuration['passphrase']
        );

        return self::sendBinaryMessage(
            $socket,
            $this->buildBinaryMessage(
                $notification->getTo('deviceToken'),
                $notification->getContent('message')
            )
        );
    }

    /**
     * Init the socket connexion.
     *
     * @param bool   $useSandbox
     * @param string $certificate
     * @param string $passphrase
     *
     * @return persistent stream $socket
     * @thrown IOSPushNotifierException
     */
    public static function createSocketConnexion($useSandbox, $certificate, $passphrase)
    {
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $certificate);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        $apnsUrl = 'ssl://gateway.push.apple.com:2195';
        if ($useSandbox) {
            $apnsUrl = 'ssl://gateway.sandbox.push.apple.com:2195';
        }

        $socket = stream_socket_client(
            $apnsUrl,
            $err,
            $errstr,
            60,
            STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT,
            $ctx
        );

        if (!$socket) {
            throw new PushIOSNotifierException(sprintf(
                'Connection failed: %s, %s',
                $err,
                $errstr
            ));
        }

        return $socket;
    }

    /**
     * Send the payload using socket connexion.
     *
     * @param persistent stream|false $socket
     * @param string                  $binaryMessage
     *
     * @return bool
     */
    public static function sendBinaryMessage($socket, $binaryMessage)
    {
        $result = fwrite($socket, $binaryMessage, strlen($binaryMessage));
        fclose($socket);

        return false === $result ? false : true;
    }

    /**
     * Build the binary notification.
     *
     * @param string $deviceToken
     * @param string $message
     *
     * @return string
     */
    protected function buildBinaryMessage($deviceToken, $message)
    {
        $payload = json_encode(array(
            'aps' => array(
                'alert' => $message,
                'sound' => 'default',
            ),
        ));

        return chr(0).pack('n', 32).pack('H*', $deviceToken).pack('n', strlen($payload)).$payload;
    }

    /**
     * {@inheritdoc}
     */
    public function getToFields()
    {
        return array(
            'deviceToken' => array('text', array('required' => true)),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getContentFields()
    {
        //256 characters max (38 characters used for others fields)
        return array(
            'message' => array('text', array('required' => true, 'max_length' => 218)),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return array(
            'certificate' => array('certificate', array('required' => false)),
            'passphrase' => array('text',        array('required' => false)),
            'useSandbox' => array('checkbox',    array('required' => false)),
        );
    }
}
