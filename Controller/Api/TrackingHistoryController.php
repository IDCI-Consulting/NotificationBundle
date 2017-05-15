<?php

namespace IDCI\Bundle\NotificationBundle\Controller\Api;

use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Entity\TrackingHistory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class GeneratorController
 *
 * @package Tms\Bundle\DocumentBundle\Controller\Api
 */
class TrackingHistoryController extends Controller
{
    /**
     * @Route("/tracking", name="idci_notification_tracking")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function trackingAction(Request $request)
    {
        $response = new Response();

        try {
            $notification = $this
                ->get('idci_notification.manager.notification')
                ->find($request->query->get('notification_id'))
            ;

            if (!$notification) {
                $this->createNotFoundException(sprintf('Unable to find Notification entity.'));
            }

            $trackingHistory = new TrackingHistory();

            $trackingHistory
                ->setNotification($notification)
                ->setAction($request->query->get('action'))
                ->setOrigin($request->getClientIp())
                ->setContext($request->headers->get('User-Agent'))
            ;

            $notification->addTrackingHistory($trackingHistory);

            $this
                ->get('idci_notification.manager.notification')
                ->update($notification)
            ;

            $response->setStatusCode(Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->setContent($e->getMessage());
        } catch (\Exception $e) {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            $response->setContent($e->getMessage());
        }

        return $response;
    }
}