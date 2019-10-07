<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\NotificationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type as Types;

class CertificateType extends AbstractType
{
    protected $configuration;

    /**
     * Constructor.
     *
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Get certificates directory.
     *
     * @return string
     */
    public function getCertificatesDirectory()
    {
        return $this->configuration['push_ios']['certificates_directory'];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path', Types\TextType::class, array('required' => false))
            ->add('file', Types\FileType::class, array('required' => false))
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreBindData'));
    }

    /**
     * On pre bind data.
     *
     * @param FormEvent $event
     */
    public function onPreBindData(FormEvent $event)
    {
        $data = $event->getData();
        $uploadedCertificate = $data['file'];

        if (null === $uploadedCertificate) {
            return;
        }

        $certificatePath = sprintf('%s/%s',
            $this->getCertificatesDirectory(),
            $uploadedCertificate->getClientOriginalName()
        );

        if (file_exists($certificatePath)) {
            throw new \Exception('A certificate with the same name already exist');
        }

        $certificate = $uploadedCertificate->move(
            $this->getCertificatesDirectory(),
            $uploadedCertificate->getClientOriginalName()
        );

        $event->setData(array('path' => $certificatePath, 'file' => null));
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
        return 'certificate';
    }
}
