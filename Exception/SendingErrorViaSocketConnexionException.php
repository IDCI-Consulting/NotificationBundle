<?php
/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Exception;

class SendingErrorViaSocketConnexionException extends \Exception
{
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct(
            "Message not delivered",
            0,
            null
        );
    }
}
