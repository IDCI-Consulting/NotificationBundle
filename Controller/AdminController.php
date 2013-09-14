<?php

namespace IDCI\Bundle\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

/**
 * Notification controller.
 *
 * @Route("/notification")
 */
class AdminController extends Controller
{
    /**
     * Lists all Notification entities.
     *
     * @Route("/")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IDCINotificationBundle:Notification')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Notification entity.
     *
     * @Route("/{id}")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IDCINotificationBundle:Notification')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notification entity.');
        }

        return array(
            'entity' => $entity,
        );
    }
}
