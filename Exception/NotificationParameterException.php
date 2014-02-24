<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Exception;

class NotificationParameterException extends \Exception
{
    /**
     * Constructor
     *
     * @param string $errorMessage
     */
    public function __construct($errorMessage)
    {
        parent::__construct($errorMessage, 0, null);
    }
}

