<?php

namespace IDCI\Bundle\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use IDCI\Bundle\SimpleMetadataBundle\Entity\Metadata;
use IDCI\Bundle\SimpleMetadataBundle\Metadata\MetadatableInterface;

/**
 * NotifierConfiguration.
 *
 * @ORM\Entity(repositoryClass="IDCI\Bundle\NotificationBundle\Entity\Repository\NotifierConfigurationRepository")
 * @ORM\Table(name="notifier_configuration", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unique_configuration", columns={"alias", "type"})
 * })
 */
class NotifierConfiguration implements MetadatableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    private $type;

    /**
     * @var array
     *
     * @ORM\Column(type="text")
     */
    private $configuration;

    /**
     * @var array<Metadata>
     *
     * @ORM\ManyToMany(targetEntity="IDCI\Bundle\SimpleMetadataBundle\Entity\Metadata", cascade={"all"})
     * @ORM\JoinTable(name="template_tag",
     *     joinColumns={@ORM\JoinColumn(name="template_id", referencedColumnName="id", onDelete="cascade")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id", unique=true, onDelete="cascade")}
     * )
     */
    private $tags;


    /**
     * toString.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('[%d] %s (%s)',
            $this->getId(),
            $this->getAlias(),
            $this->getType()
        );
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * Set alias.
     *
     * @param string $alias
     *
     * @return NotifierConfiguration
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias.
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return NotifierConfiguration
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set configuration.
     *
     * @param string $configuration
     *
     * @return NotifierConfiguration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Get configuration.
     *
     * @return string
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Add tag
     *
     * @param \IDCI\Bundle\SimpleMetadataBundle\Entity\Metadata $tag
     * @return Template
     */
    public function addTag(\IDCI\Bundle\SimpleMetadataBundle\Entity\Metadata $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \IDCI\Bundle\SimpleMetadataBundle\Entity\Metadata $tag
     */
    public function removeTag(\IDCI\Bundle\SimpleMetadataBundle\Entity\Metadata $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadatas()
    {
        return $this->getTags();
    }
}
