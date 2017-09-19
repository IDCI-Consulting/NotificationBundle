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
        // The default source name value is based on the request client IP
        $defaultSourceName = sprintf('[%s]', $this->get('request')->getClientIp());

        $sourceName = sprintf('%s %s',
            $defaultSourceName,
            $this->get('request')->request->get('sourceName', '')
        );

        $notifiers = $this->container->getParameter('idci_notification.notifiers');
        $data = $this->get('request')->request->all();
        if (isset($data['sourceName'])) {
            unset($data['sourceName']);
        }

        try {
            foreach ($data as $notificationType => $notificationData) {
                if (!in_array($notificationType, array_keys($notifiers))) {
                    throw new UndefinedNotifierException($notificationType);
                }
            }
        } catch (UndefinedNotifierException $e) {
            return $this->handleView($this->view(
                array('message' => $e->getMessage()),
                Codes::HTTP_NOT_IMPLEMENTED
            ));
        }

        try {
            foreach ($data as $notificationType => $notificationData) {
                $this
                    ->get('idci_notification.manager.notification')
                    ->processData($notificationType, $notificationData, $sourceName)
                ;
            }
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
