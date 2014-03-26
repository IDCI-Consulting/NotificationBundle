<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Exception;

class UndefindedDefinitionException extends \Exception
{
    /**
     * Constructor
     *
     * @param string $errorMessage
     */
    public function __construct($id)
    {
        parent::__construct(
            sprintf('Undefinded the definition of service %s', $id),
            0,
            null);
    }
}