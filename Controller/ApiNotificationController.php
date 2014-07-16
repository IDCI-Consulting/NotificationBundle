<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Util\Codes;
use JMS\Serializer\SerializationContext;
use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Exception\UndefinedNotifierException;
use IDCI\Bundle\NotificationBundle\Exception\NotificationParametersParseErrorException;

/**
 * Notification API REST controller
 */
class ApiNotificationController extends FOSRestController
{
    /**
     * [GET] /notifications
     * Retrieve a set of notifications
     *
     * @QueryParam(name="limit", requirements="\d+", default=20, strict=true, nullable=true, description="(optional) Pagination limit")
     * @QueryParam(name="offset", requirements="\d+", strict=true, nullable=true, description="(optional) Pagination offet")
     *
     * @param string $limit
     * @param string $offset
     */
    public function getNotificationsAction(
        $limit    = null,
        $offset   = null
    )
    {
        $criteria = array();
        $entities = $this
            ->get('idci_notification.manager.notification')
            ->findBy($criteria, null, $limit, $offset)
        ;
        $context = SerializationContext::create()->setGroups(array('list'));
        $view = $this->view($entities, Codes::HTTP_OK);
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    /**
     * [GET] /notifications/{id}
     * Retrieve a notification
     *
     * @param string $id
     */
    public function getNotificationAction($id)
    {
        $entity = $this->get('idci_notification.manager.notification')->findOneById($id);
        if (!$entity) {
            $view = $this->view(array(), Codes::HTTP_NOT_FOUND);

            return $this->handleView($view);
        }

        $context = SerializationContext::create()->setGroups(array('details'));
        $view = $this->view(
            array('class' => get_class($entity),
                  'data'  => $entity,
            ),
            Codes::HTTP_OK
        );
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    /**
     * [POST] /notifications
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
        var_dump("sent data", $data);
        var_dump("Notifies list", $notifiers);

        try {
            foreach ($data as $notificationType => $notificationData) {
                if (!in_array($notificationType, array_keys($notifiers))) {
                    throw new UndefinedNotifierException($notificationType);
                }
            }
        } catch(UndefinedNotifierException $e) {
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
