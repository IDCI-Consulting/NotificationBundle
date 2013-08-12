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
use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Exception\UnavailableNotificationDataException;

class ApiController extends Controller
{
    /**
     * Add a Notification
     *
     * @Route("/notifications/add", requirements={"_method" = "POST"}, name="notification_api_add")
     * @param Request $request
     */
    public function notifyAction(Request $request)
    {
        $response = new Response();
        $requestNotifications = $request->request->all();

        // The default source name value is based on the request client IP
        $sourceName = sprintf('[%s]', $request->getClientIp());

        // Retrieve the source name if is send
        if(isset($requestNotifications['source_name'])) {
            $sourceName = sprintf('%s %s', $sourceName, $requestNotifications['source_name']);
            unset($requestNotifications['source_name']);
        }

        $em = $this->getDoctrine()->getManager();
        try {
            foreach($requestNotifications as $type => $notificationsFeed) {
                $notificationsData = json_decode($notificationsFeed, true);
                foreach($notificationsData as $notificationData) {
                    $proxyNotification = $this
                        ->get('notification_manager')
                        ->create($type, $notificationData, $sourceName)
                    ;

                    $em->persist($proxyNotification->getNotification());
                }
            }
            $em->flush();
            $response->setStatusCode(200);
        } catch (UnavailableNotificationDataException $e) {
            $response->setContent($e->getMessage());
            $response->setStatusCode(400);
        } catch (\Exception $e) {
            $response->setContent($e->getMessage());
            $response->setStatusCode(500);
        }

        return $response;
    }
}
