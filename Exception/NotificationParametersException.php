<?php
/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Exception;

class NotificationParametersException extends \Exception
{
    /**
     * Constructor.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct(sprintf('Notification parameters error : %s', $message), 0, null);
    }
}
