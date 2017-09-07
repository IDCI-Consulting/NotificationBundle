<?php
/**
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Exception;

class EmptyFieldsConfigurationException extends \Exception
{
    /**
     * Constructor.
     *
     * @param array $configuration
     */
    public function __construct($configuration)
    {
        parent::__construct(print_r($configuration, true), 0, null);
    }
}
