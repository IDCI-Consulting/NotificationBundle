<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Exception;

class IOSPushNotifierException extends \Exception
{
    /**
     * Constructor
     *
     * @param string $errstr
     */
    public function __construct($message)
    {
        parent::__construct(sprintf(
            'IOS Push Notifier exception: %s'
            $message
        ));
    }
}