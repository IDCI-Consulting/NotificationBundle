<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\NotificationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\NotificationBundle\Notifier\NotifierInterface;
use IDCI\Bundle\NotificationBundle\Form\NotificationType;

class AbstractNotificationType extends NotificationType
{
    private $name;
    private $notifier;

    /**
     * Constructor
     *
     * @param string $name
     * @param NotifierInterface $notifier
     */
    public function __construct($name, NotifierInterface $notifier)
    {
        $this->name = $name;
        $this->notifier = $notifier;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('type', 'hidden')
            ->remove('log')
        ;

        if ($this->notifier->getFromFields()) {
            $builder->add('from', 'metadata', array(
                'fields' => $this->notifier->getFromFields()
            ));
        }

        if ($this->notifier->getToFields()) {
            $builder->add('to', 'metadata', array(
                'fields' => $this->notifier->getToFields()
            ));
        }

        if ($this->notifier->getContentFields()) {
            $builder->add('content', 'metadata', array(
                'fields' => $this->notifier->getContentFields()
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return sprintf('notification_%s', $this->name);
    }
}
