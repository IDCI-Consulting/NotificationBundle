<?php

/**
 * @license: MIT
 */

namespace IDCI\Bundle\NotificationBundle\Exception;

/**
 * Class JsonConversionException.
 */
class JsonConversionException extends \InvalidArgumentException
{
    /**
     * constructor.
     *
     * @param string $method
     * @param string $errorMessage
     */
    public function __construct($method, $errorMessage)
    {
        parent::__construct(sprintf(
            'JsonConversionException - %s: %s',
            $method,
            $errorMessage
        ));
    }
}
