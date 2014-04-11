<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet Puth <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Exception;

class NotificationFieldParseErrorException extends \Exception
{
    /**
     * Constructor
     *
     * @param string $notificationField
     */
    public function __construct($notificationField)
    {
        parent::__construct(
            sprintf(
                'Notification field parse error : %s',
                $notificationField
            ),
            0,
            null
        );
    }
}
