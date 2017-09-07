<?php
/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Exception;

class NotificationParametersParseErrorException extends \Exception
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
                'Wrong Notification parameters : %s',
                $configuration
            ),
            0,
            null
        );
    }
}
