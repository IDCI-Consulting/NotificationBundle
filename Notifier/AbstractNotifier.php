<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManager;
use IDCI\Bundle\NotificationBundle\Util\Inflector;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

abstract class AbstractNotifier implements NotifierInterface
{
    protected $entityManager;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Get EntityManager
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(Notification $notification)
    {
        if (null === $notification->getFrom()) {
            //return the symfony configuration with default values
            return array();
        }

        $from = json_decode($notification->getFrom(), true);

        if (!$from) {
             throw new ConfigurationParseErrorException($notification->getFrom());
        }

        if (isset($from['alias'])) {
            //return the configuration from provider parameters
            $em = $this->getEntityManager();
            $notifierConfiguration = $em
                ->getRepository('IDCINotificationBundle:NotifierConfiguration')
                ->findBy(array(
                    'alias' => $from['alias'],
                    'type'  => $notification->getType()
                ))
            ;

            if (null === $notifierConfiguration) {
                throw new UndefinedNotifierConfigurationException($from['alias'], $notification->getType());
            }

            if ($configuration = json_decode($notifierConfiguration, true)) {
                return $configuration;
            }

            throw new ConfigurationParseErrorException($notifierConfiguration);
        }

        return $from;
    }

    /**
     * {@inheritdoc}
     */
    public function cleanData($data)
    {
        foreach ($data as $field => $options) {
            $fieldOptions = $this->getFieldOptions($field);
            $data[$field] = $this->getResolver($fieldOptions)->resolve($options);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver(array $options)
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver, $options);

        return $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldOptions($field)
    {
        $method = sprintf('get%sFields', ucfirst(strtolower($field)));

        return $this->$method();
    }

    /**
     * {@inheritdoc}
     */
    public function getToFields()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getContentFields()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver, array $fieldOptions)
    {
        foreach ($fieldOptions as $name => $options) {
            $resolver->setOptional(array($name));

            $hasChoices = isset($options[1]) && isset($options[1]['choices']) && count($options[1]['choices']) > 0 ? true : false;
            if ($hasChoices) {
                $resolver->setAllowedValues(array(
                    $name => array_keys($options[1]['choices'])
                ));
            }
        }

    }
}