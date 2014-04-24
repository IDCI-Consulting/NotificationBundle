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
use IDCI\Bundle\NotificationBundle\Exception\NotificationFieldParseErrorException;
use IDCI\Bundle\NotificationBundle\Exception\InitSocketConnexionFailedException;
use IDCI\Bundle\NotificationBundle\Exception\SendingErrorViaSocketConnexionException;

class IOSPushNotifier extends AbstractNotifier
{
    /**
     * {@inheritdoc}
     */
    public function sendNotification(Notification $notification)
    {
        //$notification->getTo()
        //$notification->getTo('deviceToken')
        $deviceToken = json_decode($notification->getTo(), true);
        if (null === $deviceToken) {
            throw new NotificationFieldParseErrorException($notification->getTo());
        }

        $passphrase = json_decode($notification->getFrom(), true);
        if (null === $passphrase) {
            throw new NotificationFieldParseErrorException($notification->getFrom());
        }

        $message = json_decode($notification->getContent(), true);
        if (null === $message) {
            throw new NotificationFieldParseErrorException($notification->getContent());
        }

        // Encode the payload as JSON
        $payload = json_encode($this->createPayloadBody($message['message']));
        // Build the binary notification
        $msg = $this->buildBinaryNotification($deviceToken['deviceToken'], $payload);

        $fp = sefl::initSocketConnexion($passphrase['passphrase']);
        sefl::sendViaSocketConnexion($fp, $msg);

    }

    /**
     * Init the socket connexion
     *
     * @param string $passphrase
     * @thrown SocketConnexionFailedException
     */
    public static function initSocketConnexion($passphrase)
    {
        $context = stream_context_create(); //Crée un contexte de flux
        //Configure une option pour un flux/gestionnaire/contexte
        stream_context_set_option($context, 'ssl', 'local_cert', 'ck.pem');
        stream_context_set_option($context, 'ssl', 'passphrase', $passphrase);
        // Open a connection to the APNS server
        // Ouvre une connexion socket Internet ou Unix
        // return false if connection error
        $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195',
            $err,
            $errstr,
            60,
            STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT,
            $context
        );

        if (!$fp) {
            throw new InitSocketConnexionFailedException($err, $errstr);
        }

        return $fp;
    }

    /**
     * Send the payload using socket connexion
     *
     * @param TODO   $fp
     * @param string $msg
     * @throw SendingErrorViaSocketConnexionException
     */
    public static function sendViaSocketConnexion($fp, $msg)
    {
        // Send it to the server
        // Écrit un fichier en mode binaire
        $result = fwrite($fp, $msg, strlen($msg));

        if (!$result) {
            throw new SendingErrorViaSocketConnexionException()
        }

        // Close the connection to the server
        // Ferme un fichier
        fclose($fp);
    }


    /**
     * Create the payload body
     *
     * @param string $message
     * @return array $body
     */
    protected function createPayloadBody($message)
    {
        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'default'
        );

        return $body;
    }

    /**
     * Build the binary notification
     *
     * @param string $deviceToken
     * @param string $payload
     * @return string
     */
    protected function buildBinaryNotification($deviceToken, $payload)
    {
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
            'passphrase' => array('text', array('required' => true))
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