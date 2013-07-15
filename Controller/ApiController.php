<?php

namespace IDCI\Bundle\NotificationBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IDCI\Bundle\NotificationBundle\Exception\UnavailableNotificationData;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

class ApiController extends Controller
{
    /**
     * Allow to notify
     * @Route("/notify", requirements={"_method" = "POST"}, name="notification_api_notify")
     */
    public function notifyAction(Request $request)
    {
        $response = new Response();
        $requestNotifications = $request->request->all();

        var_dump($requestNotifications);

        $em = $this->getDoctrine()->getManager();
        try {
            foreach($requestNotifications as $type => $notificationsFeed) {
                $notificationsData = json_decode($notificationsFeed, true);
                foreach($notificationsData as $notificationData) {
                    $notificationInterface = $this
                        ->get('notification_manager')
                        ->createFromArray($type, $notificationData)
                    ;

                    $em->persist($notificationInterface->convertToNotification());
                }
            }
            $em->flush();
            $response->setStatusCode(204);
        } catch (UnavailableNotificationData $e) {
            $response->setContent($e->getMessage());
            $response->setStatusCode(400);
        } catch (\Exception $e) {
            $response->setContent($e->getMessage());
            $response->setStatusCode(500);
        }
        
        return $response;       
    }
}
