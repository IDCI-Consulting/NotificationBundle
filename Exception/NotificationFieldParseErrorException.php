<?php
/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Exception;

class NotificationFieldParseErrorException extends \Exception
{
    /**
     * Constructor
     *
     * @param string $field
     */
    public function __construct($field)
    {
        parent::__construct(
            sprintf(
                'Parse error in field : %s',
                $field
            ),
            0,
            null
        );
    }
}

