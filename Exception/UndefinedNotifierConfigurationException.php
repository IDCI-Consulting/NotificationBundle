<?php
/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Exception;

class UndefinedNotifierConfigurationException extends \Exception
{
    /**
     * Constructor.
     *
     * @param string $alias
     * @param string $type
     */
    public function __construct($alias, $type = null)
    {
        $message = sprintf('Undefined NotifierConfiguration (alias : %s)', $alias);
        if (null !== $type) {
            $message = sprintf('Undefined NotifierConfiguration (alias : %s and type : %s)',
                $alias,
                $type
            );
        }

        parent::__construct($message, 0, null);
    }
}
