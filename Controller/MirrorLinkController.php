<?php

namespace IDCI\Bundle\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Entity\TrackingHistory;

class MirrorLinkController extends Controller
{
    /**
     * @Route("/mirror-link/{hash}", name="idci_notification_mirror_link")
     * @ParamConverter("notification", class="IDCINotificationBundle:Notification")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function mirrorLinkAction(Request $request, Notification $notification)
    {
        $content = json_decode($notification->getContent(), true);

        $response = new Response();
        $response->setContent(self::purgeMirrorLink($content['htmlMessage']));

        $trackingHistory = new TrackingHistory();

        $trackingHistory
            ->setNotification($notification)
            ->setAction('mirror link open')
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

        return $response;
    }

    /**
     * Purge the mirror link if present
     *
     * @param string $content
     */
    public static function purgeMirrorLink($content)
    {
        return preg_replace(
            array('#<a[^>]*href=\"\[\[mirrorlink\]\]\".*<\/a>#U'),
            '',
            $content
        );
    }
}
