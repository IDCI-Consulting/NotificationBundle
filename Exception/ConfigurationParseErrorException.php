<?php
/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Exception;

class ConfigurationParseErrorException extends \Exception
{
    /**
     * Constructor.
     *
     * @param string $configuration
     */
    public function __construct($configuration)
    {
        parent::__construct(
            sprintf(
                'Wrong configuration: %s',
                $configuration
            ),
            0,
            null
        );
    }
}
