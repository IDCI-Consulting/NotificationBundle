<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Entity\TrackingHistory;

class TrackingHistoryController extends Controller
{
    /**
     * @Route("/tracking/{id}", name="idci_notification_tracking")
     * @ParamConverter("notification", class="IDCINotificationBundle:Notification")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function trackingAction(Request $request, Notification $notification)
    {
        $response = new Response();
        $trackingHistory = new TrackingHistory();

        $trackingHistory
            ->setNotification($notification)
            ->setAction($request->query->get('action'))
            ->setOrigin($request->getClientIp())
            ->setContext(json_encode(
                array('user-agent' => $request->headers->get('User-Agent'))
            ))
        ;

        $notification->addTrackingHistory($trackingHistory);

        $this
            ->get('idci_notification.manager.notification')
            ->update($notification)
        ;

        $response->setStatusCode(Response::HTTP_OK);

        return new TrackingPixelResponse();
    }
}
