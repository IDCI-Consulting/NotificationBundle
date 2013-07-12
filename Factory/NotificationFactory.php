<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Factory;

use IDCI\Bundle\NotificationBundle\Model\NotificationInterface;

class NotificationFactory
{
	private function __construct()
    {

    }

    /**
     * Create
     *
     * @param string $type
     * @param array $parameters
     * @return NotificationInterface
     */
    public function create($type, $parameters)
    {
    }
}
