<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Util\Inflector;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractNotifier implements NotifierInterface
{
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
            $isRequired = isset($options[1]) && isset($options[1]['required']) ? $options[1]['required'] : false;
            if ($isRequired) {
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
