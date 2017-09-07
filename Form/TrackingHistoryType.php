<?php

/**
 *
 * @author:  Rémy MENCE <remymence@gmail.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\NotificationBundle\Entity\TrackingHistory;

class TrackingHistoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('action')
            ->add('origin')
            ->add('context')
            ->add('createdAt');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => 'IDCI\Bundle\NotificationBundle\Entity\TrackingHistory'
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'idci_bundle_notificationbundle_trackinghistorytype';
    }
}
