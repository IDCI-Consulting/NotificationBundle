<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Util\Inflector;

abstract class AbstractNotifier implements NotifierInterface
{
    /**
     * {@inheritdoc}
     */
    public function dataValidationMap()
    {
        return array();
    }
}
