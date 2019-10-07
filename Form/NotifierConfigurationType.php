<?php
/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTHv <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Types;
use IDCI\Bundle\NotificationBundle\Form\Type\NotifierChoiceType;
use IDCI\Bundle\SimpleMetadataBundle\Form\Type\RelatedToManyMetadataType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use IDCI\Bundle\SimpleMetadataBundle\Form\Type\MetadataType;

class NotifierConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alias', Types\TextType::class)
            ->add('type', NotifierChoiceType::class)
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $notifier = $event->getForm()->getConfig()->getOptions()['notifier'];

            if (null !== $notifier && $notifier->getFromFields()) {
                dump($notifier->getFromFields());
                $form->add('configuration', MetadataType::class, array(
                    'fields' => $notifier->getFromFields(),
                ));
            }
        });

        $builder->add('tags', RelatedToManyMetadataType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IDCI\Bundle\NotificationBundle\Entity\NotifierConfiguration',
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
        return 'idci_bundle_notificationbundle_notifierconfigurationtype';
    }
}
