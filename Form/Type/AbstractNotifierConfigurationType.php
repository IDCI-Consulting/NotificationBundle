<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\NotificationBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use IDCI\Bundle\NotificationBundle\Notifier\NotifierInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use IDCI\Bundle\SimpleMetadataBundle\Form\Type\MetadataType;
use Symfony\Component\Form\AbstractType;

class AbstractNotifierConfigurationType extends AbstractType
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
        $builder
            ->add('type', HiddenType::class)
        ;

        if ($this->notifier->getFromFields()) {
            $builder->add('configuration', MetadataType::class, array(
                'fields' => $this->notifier->getFromFields(),
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
        return sprintf('notifier_configuration_%s', $this->name);
    }
}
