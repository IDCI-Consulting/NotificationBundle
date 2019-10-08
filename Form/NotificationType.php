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
use Symfony\Component\Form\Extension\Core\Type as Types;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use IDCI\Bundle\SimpleMetadataBundle\Form\Type\MetadataType;

class NotificationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', Types\ChoiceType::class, array(
                'choices' => Notification::getStatusList(),
            ))
            ->add('priority', Types\ChoiceType::class, array(
                'choices' => Notification::getPriorityList(),
            ))
            ->add('notifierAlias')
            ->add('from')
            ->add('to')
            ->add('content', Types\TextareaType::class)
            ->add('source')
            ->add('log')
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $notifier = $event->getForm()->getConfig()->getOptions()['notifier'];

            if (null !== $notifier) {
                $form
                    ->add('type', Types\HiddenType::class)
                    ->remove('log');

                if ($notifier->getFromFields()) {
                    $form->add('from', MetadataType::class, array(
                        'fields' => $notifier->getFromFields(),
                    ));
                } else {
                    $form->remove('from');
                }

                if ($notifier->getToFields()) {
                    $form->add('to', MetadataType::class, array(
                        'fields' => $notifier->getToFields(),
                    ));
                } else {
                    $form->remove('to');
                }

                if ($notifier->getContentFields()) {
                    $form->add('content', MetadataType::class, array(
                        'fields' => $notifier->getContentFields(),
                    ));
                } else {
                    $form->remove('content');
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IDCI\Bundle\NotificationBundle\Entity\Notification',
            'notifier' => null,
        ));
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
