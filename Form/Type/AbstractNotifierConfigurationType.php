<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\NotificationBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use IDCI\Bundle\NotificationBundle\Notifier\NotifierInterface;
use IDCI\Bundle\NotificationBundle\Form\NotifierConfigurationType;

class AbstractNotifierConfigurationType extends NotifierConfigurationType
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
            ->add('type', 'hidden')
        ;

        if ($this->notifier->getFromFields()) {
            $builder->add('configuration', 'metadata', array(
                'fields' => $this->notifier->getFromFields(),
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return sprintf('notifier_configuration_%s', $this->name);
    }
}
