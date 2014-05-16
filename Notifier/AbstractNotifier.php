<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Doctrine\ORM\EntityManager;
use IDCI\Bundle\NotificationBundle\Util\Inflector;
use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Exception\ConfigurationParseErrorException;
use IDCI\Bundle\NotificationBundle\Exception\UndefinedNotifierConfigurationException;

abstract class AbstractNotifier implements NotifierInterface
{
    protected $entityManager;
    protected $defaultConfiguration;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager
     * @param array         $defaultConfiguration
     */
    public function __construct(EntityManager $entityManager, $defaultConfiguration)
    {
        $this->entityManager = $entityManager;
        $this->defaultConfiguration = $defaultConfiguration;
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
        if ($notification->hasNotifierAlias()) {
            try {
                return $this->getDataBaseConfiguration(
                    $notification->getNotifierAlias(),
                    $notification->getType()
                );
            } catch(UndefinedNotifierConfigurationException $e) {
                return $this->getFileConfiguration($notification->getNotifierAlias());
            }
        }

        if (null !== $notification->getFrom()) {
            $from = json_decode($notification->getFrom(), true);
            if (null === $from) {
                throw new ConfigurationParseErrorException($notification->getFrom());
            } elseif (count($from) > 0) {
                return $from;
            }
        }

        return $this->getFileConfiguration();
    }

    /**
     * Get file configuration
     *
     * @param  string $alias
     * @return array
     * @throw  UndefinedNotifierConfigurationException
     */
    protected function getFileConfiguration($alias = null)
    {
        if (null === $alias) {
            $alias = $this->defaultConfiguration['default_configuration'];
        }

        if(!isset($this->defaultConfiguration['configurations'][$alias])) {
            throw new UndefinedNotifierConfigurationException($alias);
        }

        return $this->defaultConfiguration['configurations'][$alias];
    }

    /**
     * Get configuration from database
     *
     * @param  string $alias
     * @param  string $type
     * @return array
     * @throw  UndefinedNotifierConfigurationException
     * @throw  ConfigurationParseErrorException
     */
    protected function getDataBaseConfiguration($alias, $type)
    {
        $em = $this->getEntityManager();
        $notifierConfiguration = $em
            ->getRepository('IDCINotificationBundle:NotifierConfiguration')
            ->findOneBy(array(
                'alias' => $alias,
                'type'  => $type
            ))
        ;

        if (null === $notifierConfiguration) {
            throw new UndefinedNotifierConfigurationException($alias, $type);
        }

        if ($configuration = json_decode($notifierConfiguration->getConfiguration(), true)) {
            return $configuration;
        }

        throw new ConfigurationParseErrorException($notifierConfiguration);
    }

    /**
     * {@inheritdoc}
     */
    public function cleanData($data)
    {
        foreach ($data as $field => $options) {
            if (is_array($options)) {
                $fieldOptions = $this->guessFieldOptions($field);
                try {
                    $options = $this->getResolver($fieldOptions)->resolve($options);
                } catch (MissingOptionsException $e) {
                    throw new MissingOptionsException(sprintf(
                        'Error in "%s" field: %s',
                        $field,
                        $e->getMessage()
                    ));
                }
            }
            $data[$field] = $options;
        }

        return $data;
    }

    /**
     * Get resolver
     *
     * @param array $fieldOptions
     *
     * @return $resolver
     */
    protected function getResolver(array $fieldOptions)
    {
        $resolver = new OptionsResolver();
        $this->configureDefaultOptions($resolver, $fieldOptions);

        return $resolver;
    }

    /**
     * Guess field options
     *
     * @param string $field The name of field("from", "to", "content")
     *
     * @return array
     */
    protected function guessFieldOptions($field)
    {
        $method = sprintf('get%sFields', ucfirst(strtolower($field)));

        return $this->$method();
    }

    /**
     * {@inheritdoc}
     */
    public function getToFields()
    {
        return array('to' => array('textarea', array('required' => false)));
    }

    /**
     * {@inheritdoc}
     */
    public function getContentFields()
    {
        return array('message' => array('textarea', array('required' => false)));
    }

    /**
     * {@inheritdoc}
     */
    public function getFromFields()
    {
        return array('from' => array('text', array('required' => false)));
    }

    /**
     * Configure OptionResolver with default options
     *
     * @param OptionsResolver $resolver
     * @param array           $fieldOptions
     */
    protected function configureDefaultOptions(OptionsResolver $resolver, array $fieldOptions)
    {
        foreach ($fieldOptions as $name => $options) {
            if (isset($options[1]) && isset($options[1]['required']) && $options[1]['required']) {
                $resolver->setRequired(array($name));
            } else {
                $resolver->setOptional(array($name));
            }

            $hasChoices = isset($options[1]) && isset($options[1]['choices']) && count($options[1]['choices']) > 0 ? true : false;
            if ($hasChoices) {
                $resolver->setAllowedValues(array(
                    $name => array_keys($options[1]['choices'])
                ));
            }
        }
    }
}
