<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\NotificationBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use IDCI\Bundle\NotificationBundle\Notifier\NotifierInterface;
use IDCI\Bundle\NotificationBundle\Form\NotificationType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use IDCI\Bundle\SimpleMetadataBundle\Form\Type\MetadataType;

class AbstractNotificationType extends NotificationType
{
    private $name;
    private $notifier;

    /**
     * Constructor.
     *
     * @param string            $name
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
            ->add('type', HiddenType::class)
            ->remove('log')
        ;

        if (!$this->notifier->getFromFields()) {
            $builder->remove('from');
        } else {
            $builder->add('from', MetadataType::class, array(
                'fields' => $this->notifier->getFromFields(),
            ));
        }

        if (!$this->notifier->getToFields()) {
            $builder->remove('to');
        } else {
            $builder->add('to', MetadataType::class, array(
                'fields' => $this->notifier->getToFields(),
            ));
        }

        if (!$this->notifier->getContentFields()) {
            $builder->remove('content');
        } else {
            $builder->add('content', MetadataType::class, array(
                'fields' => $this->notifier->getContentFields(),
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return sprintf('notification_%s', $this->name);
    }
}
