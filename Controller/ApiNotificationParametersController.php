<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Util\Codes;
use JMS\Serializer\SerializationContext;

/**
 * NotificationParameters API REST controller
 */
class ApiNotificationParametersController extends FOSRestController
{
    /**
     * [GET] /notificationsparameters
     * Return parameters for all type of notification
     *
     */
    public function getNotificationsparametersAction()
    {
        $notifiers = $this->container->getParameter('idci_notification.notifiers');
        $notificationsParameters = array();
        foreach($notifiers as $key => $value) {
            $notifier = $this->get(sprintf("idci_notification.notifier.%s", $value));
            $notificationsParameters[$value]["to"] = ($notifier->getToFields())
                ? $notifier->getToFields()
                : null
            ;
            $notificationsParameters[$value]["from"] = ($notifier->getFromFields())
                ? $notifier->getFromFields()
                : null
            ;
            $notificationsParameters[$value]["content"] = ($notifier->getContentFields())
                ? $notifier->getContentFields()
                : null
            ;

            $notificationsParameters[$value] = $notifier
                ->cleanEmptyValue($notificationsParameters[$value])
            ;
        }

        $context = SerializationContext::create()->setGroups(array('list'));
        $view = $this->view($notificationsParameters, Codes::HTTP_OK);
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    /**
     * [GET] /notificationparameters/{type}
     * Return parameters of a specific notification
     *
     * @QueryParam(name="field", nullable=true, description="(optional) notification field (to, from, content)")
     *
     * @param string $type
     * @param string $field
     */
    public function getNotificationparametersAction($type, $field = null)
    {
        $notifiers = $this->container->getParameter('idci_notification.notifiers');
        $notificationParameters = array();

        if (!in_array($type, $notifiers)) {
            $view = $this->view(
                array("Error" => $type . " is not a valide type."),
                Codes::HTTP_NOT_FOUND
            );

            return $this->handleView($view);
        }

        $notifier = $this->get(sprintf("idci_notification.notifier.%s", $type));

        if ($field) {
            $getField = sprintf(
                "get%sFields",
                ucfirst($field)
            );
            try {
                $notificationParameters[$field] = ($notifier->$getField())
                    ? $notifier->$getField()
                    : null
                ;
                $cleanedNotificationParameters = $notifier
                    ->cleanEmptyValue($notificationParameters)
                ;
                if (empty($cleanedNotificationParameters)) {
                    return $this->handleView($this->view(
                        array("message" => "No data associated with the field : " . $field),
                        Codes::HTTP_NOT_FOUND
                    ));
                }

                return $this->handleView($this->view(
                    $notifier->cleanEmptyValue($notificationParameters),
                    Codes::HTTP_OK
                ));
            } catch(\Exception $e) {
                $view = $this->view(
                    array("message" => $e->getMessage()),
                    Codes::HTTP_NOT_FOUND
                );

                return $this->handleView($view);
            }
        }

        $notificationParameters["to"] = ($notifier->getToFields())
            ? $notifier->getToFields()
            : null
        ;
        $notificationParameters["from"] = $notifier->getFromFields()
            ? $notifier->getFromFields()
            : null
        ;
        $notificationParameters["content"] = $notifier->getContentFields()
            ? $notifier->getContentFields()
            : null
        ;

        $context = SerializationContext::create()->setGroups(array('list'));
        $view = $this->view(
            $notifier->cleanEmptyValue($notificationParameters),
            Codes::HTTP_OK
        );
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }
}