<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Exception\UnavailableNotificationDataException;
use IDCI\Bundle\NotificationBundle\Exception\UnavailableNotificationParameterException;
use IDCI\Bundle\NotificationBundle\Exception\UndefinedNotifierException;
use IDCI\Bundle\NotificationBundle\Exception\NotificationParameterException;
use IDCI\Bundle\NotificationBundle\Exception\NotificationParametersParseErrorException;

class ApiController extends Controller
{
    /**
     * Add a Notification
     *
     * @Route("/notifications", name="notification_api_post")
     * @Method("POST")
     */
    public function notifyAction(Request $request)
    {
        $response = new Response();

        $requestNotifications = $request->request->all();
        // The default source name value is based on the request client IP
        $sourceName = sprintf('[%s]', $request->getClientIp());

        // Retrieve the source name if sent
        if (isset($requestNotifications['source_name'])) {
            $sourceName = sprintf('%s %s', $sourceName, $requestNotifications['source_name']);
            unset($requestNotifications['source_name']);
        }

        try {
            if (empty($requestNotifications)) {
                throw new NotificationParameterException('No parameters given');
            }

            $notifers = $this->container->getParameter('idci_notification.notifiers');
            foreach ($requestNotifications as $type => $notificationsFeed) {
                if (!in_array($type, $notifers)) {
                    throw new UndefinedNotifierException($type);
                }
                $notificationsData = json_decode($notificationsFeed, true);
                if (!$notificationsData) {
                    throw new NotificationParametersParseErrorException($notificationsFeed);
                }
                foreach ($notificationsData as $notificationData) {
                    $this
                        ->get('idci_notification.manager.notification')
                        ->addNotification($type, $notificationData, $sourceName)
                    ;
                }

            }

            $response->setStatusCode(200);
        } catch (UnavailableNotificationDataException $e) {
            $response->setContent($e->getMessage());
            $response->setStatusCode(400);
        } catch (UnavailableNotificationParameterException $e) {
            $response->setContent($e->getMessage());
            $response->setStatusCode(400);
        } catch (NotificationParameterException $e) {
            $response->setContent($e->getMessage());
            $response->setStatusCode(400);
        } catch (\Exception $e) {
            $response->setContent($e->getMessage());
            $response->setStatusCode(500);
        }

        return $response;
    }
}
