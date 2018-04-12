<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Exception\UndefinedNotifierException;
use IDCI\Bundle\NotificationBundle\Exception\NotificationParametersParseErrorException;

/**
 * Notification API REST controller.
 */
class NotificationController extends FOSRestController
{
    /**
     * [POST] /notifications.
     *
     * Create a notification
     */
    public function postNotificationsAction()
    {
        $rawData = $this->get('request')->request->all();

        // The default source name value is based on the request client IP
        $sourceName = trim(sprintf('[%s] %s',
            $this->get('request')->getClientIp(),
            $this->get('request')->request->get('sourceName', '')
        ));

        if (!isset($rawData['type'])) {
            return $this->handleView($this->view(
                array('message' => 'The parameter \'type\' is missing'),
                Codes::HTTP_BAD_REQUEST
            ));
        }
        $notificationType = $rawData['type'];

        if (!isset($rawData['data'])) {
            return $this->handleView($this->view(
                array('message' => 'The parameter \'data\' is missing'),
                Codes::HTTP_BAD_REQUEST
            ));
        }
        $notificationData = $rawData['data'];

        try {
            $this
                ->get('idci_notification.manager.notification')
                ->processData($notificationType, $notificationData, $sourceName)
            ;
        } catch (UndefinedNotifierException $e) {
            return $this->handleView($this->view(
                array('message' => $e->getMessage()),
                Codes::HTTP_NOT_IMPLEMENTED
            ));
        } catch (NotificationParametersParseErrorException $e) {
            return $this->handleView($this->view(
                array('message' => $e->getMessage()),
                Codes::HTTP_BAD_REQUEST
            ));
        } catch (\Exception $e) {
            return $this->handleView($this->view(
                array('message' => $e->getMessage()),
                Codes::HTTP_INTERNAL_SERVER_ERROR
            ));
        }

        return $this->handleView($this->view(
            null,
            Codes::HTTP_CREATED
        ));
    }
}
