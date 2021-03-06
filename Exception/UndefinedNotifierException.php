<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Exception;

class UndefinedNotifierException extends \Exception
{
    /**
     * Constructor.
     *
     * @param string $notifierServiceName
     */
    public function __construct($notifierServiceName)
    {
        parent::__construct(sprintf("Undefined notifier '%s'", $notifierServiceName), 0, null);
    }
}
