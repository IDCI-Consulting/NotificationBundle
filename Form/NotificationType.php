<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\NotificationBundle\Entity\Notification;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use IDCI\Bundle\NotificationBundle\Form\Type\NotifierChoiceType;

class NotificationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', ChoiceType::class, array(
                'choices' => Notification::getStatusList(),
            ))
            ->add('priority', ChoiceType::class, array(
                'choices' => Notification::getPriorityList(),
            ))
            ->add('type', NotifierChoiceType::class)
            ->add('notifierAlias')
            ->add('from')
            ->add('to')
            ->add('content', 'textarea')
            ->add('source')
            ->add('log')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => 'IDCI\Bundle\NotificationBundle\Entity\Notification',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
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
        return 'idci_bundle_notificationbundle_notificationtype';
    }
}
