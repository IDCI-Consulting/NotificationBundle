<?php

namespace IDCI\Bundle\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NotifierConfiguration
 *
 * @ORM\Entity()
 * @ORM\Table(name="notifier_configuration", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unique_configuration", columns={"alias", "type"})
 * })
 */
class NotifierConfiguration
{
    /**
     * @var integer
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
     * toString
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return NotifierConfiguration
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return NotifierConfiguration
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set configuration
     *
     * @param string $configuration
     * @return NotifierConfiguration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Get configuration
     *
     * @return string
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}