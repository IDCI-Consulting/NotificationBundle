<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

class NotificationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'notifier_choice')
            ->add('from')
            ->add('to', 'json')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('status', 'choice', array(
                'choices' => Notification::getStatusList()
            ))
            ->add('content', 'json')
            ->add('source')
            ->add('log')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => 'IDCI\Bundle\NotificationBundle\Entity\Notification'
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'idci_bundle_notificationbundle_notificationtype';
    }
}
