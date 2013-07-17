<?php

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
     */
    public function notifyAction(Request $request)
    {
        $response = new Response();
        $requestNotifications = $request->request->all();

        $em = $this->getDoctrine()->getManager();
        try {
            foreach($requestNotifications as $type => $notificationsFeed) {
                $notificationsData = json_decode($notificationsFeed, true);
                foreach($notificationsData as $notificationData) {
                    $notificationInterface = $this
                        ->get('notification_manager')
                        ->create($type, $notificationData)
                    ;

                    $em->persist($notificationInterface->toNotification());
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
