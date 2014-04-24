<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Exception;

class SocketConnexionFailedException extends \Exception
{
    /**
     * Constructor
     *
     * @param string $errorMessage
     */
    public function __construct($err, $errstr)
    {
        parent::__construct(
            sprintf(
                'Failed to connect : %s, %s',
                $err,
                $errstr
            ),
            0,
            null
        );
    }
}