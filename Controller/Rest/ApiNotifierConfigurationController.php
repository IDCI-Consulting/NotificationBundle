<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Controller\Rest;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Util\Codes;
use JMS\Serializer\SerializationContext;

/**
 * NotifierConfiguration API REST controller.
 */
class ApiNotifierConfigurationController extends FOSRestController
{
    /**
     * [GET] /notifierconfigurations
     * Retrieve a set of notifer configurations.
     *
     * @QueryParam(name="limit", requirements="\d+", default=20, strict=true, nullable=true, description="(optional) Pagination limit")
     * @QueryParam(name="offset", requirements="\d+", strict=true, nullable=true, description="(optional) Pagination offet")
     *
     * @param string $limit
     * @param string $offset
     */
    public function getNotifierconfigurationsAction(
        $limit = null,
        $offset = null
    ) {
        $criteria = array();
        $entities = $this
            ->get('idci_notification.manager.notifierconfiguration')
            ->findBy($criteria, null, $limit, $offset)
        ;
        $context = SerializationContext::create()->setGroups(array('list'));
        $view = $this->view($entities, Codes::HTTP_OK);
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    /**
     * [GET] /notifierconfigurations/{id}
     * Retrieve a notiferconfiguration.
     *
     * @param string $id
     */
    public function getNotifierconfigurationAction($id)
    {
        $entity = $this->get('idci_notification.manager.notifierconfiguration')->findOneById($id);
        if (!$entity) {
            $view = $this->view(array(), Codes::HTTP_NOT_FOUND);

            return $this->handleView($view);
        }

        $context = SerializationContext::create()->setGroups(array('details'));
        $view = $this->view(
            array('class' => get_class($entity),
                  'data' => $entity,
            ),
            Codes::HTTP_OK
        );
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }
}
