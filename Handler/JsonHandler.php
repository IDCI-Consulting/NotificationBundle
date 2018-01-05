<?php

/**
 * @license MIT
 */

namespace IDCI\Bundle\NotificationBundle\Handler;

use IDCI\Bundle\NotificationBundle\Exception\JsonConversionException;

/**
 * Class JsonHandler
 *
 * @package IDCI\Bundle\NotificationBundle\Handler
 */
class JsonHandler
{

    /**
     * The error codes and error messages of the last json_encode() or json_decode() call
     * which may be returned by json_last_error()
     *
     * @var array
     */
    protected static $messages = array(
        JSON_ERROR_NONE => 'No error has occurred',
        JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
        JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX => 'Syntax error',
        JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
        JSON_ERROR_RECURSION => 'One or more recursive references in the value to be encoded',
        JSON_ERROR_INF_OR_NAN => 'One or more NAN or INF values in the value to be encoded',
        JSON_ERROR_UNSUPPORTED_TYPE => 'A value of a type that cannot be encoded was given',
    );

    /**
     * Returns the JSON representation of a value
     *
     * @param mixed $value   The value being encoded.
     * @param int   $options Bitmask of JSON encode options.
     * @param int   $depth   Set the maximum depth. Must be greater than zero.
     *
     * @return string
     *
     * @throws \Exception
     *
     * @codeCoverageIgnore
     */
    public static function encode($value, $options = 0, $depth = 512)
    {
        $result = json_encode($value, $options, $depth);

        if (json_last_error() == JSON_ERROR_NONE) {
            return $result;
        }

        $errorMessage = isset(static::$messages[json_last_error()])
            ? static::$messages[json_last_error()]
            : 'Unknown error'
        ;

        throw new JsonConversionException('encode', $errorMessage);
    }

    /**
     * Decodes a JSON string
     *
     * @param string $json    The json string being decoded.
     * @param bool   $assoc   When TRUE, returned objects will be converted into associative arrays.
     * @param int    $depth   User specified recursion depth.
     * @param int    $options Bitmask of JSON decode options.
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public static function decode($json, $assoc = false, $depth = 512, $options = 0)
    {
        $result = json_decode($json, $assoc, $depth, $options);

        if (json_last_error() == JSON_ERROR_NONE) {
            return $result;
        }

        $errorMessage = isset(static::$messages[json_last_error()])
            ? static::$messages[json_last_error()]
            : 'Unknown error'
        ;

        throw new JsonConversionException('decode', $errorMessage);
    }

    /**
     * Is json
     *
     * @param mixed $var         The variable being evaluated.
     * @param bool  $return_data When TRUE, returned result of json_decode
     * @param bool  $assoc       json_decode Parameter
     * @param int   $depth       json_decode Parameter
     * @param int   $options     json_decode Parameter
     *
     * @return mixed return array, when $var is json and $return_data is true,
     *                      bool, otherwise.
     */
    public static function is_json($var, $return_data = false, $assoc = false, $depth = 512, $options = 0)
    {
        if (is_string($var)) {
            $data = json_decode($var, $assoc, $depth, $options);

            return (json_last_error() == JSON_ERROR_NONE)
                ? ($return_data ? $data : true)
                : false
            ;
        }

        return false;
    }

    /**
     * Recursion do json_decode on a N-dimensional array
     *
     * @param array $current N-dimensional array who contain json string as value
     * @param bool  $assoc   json_decode Parameter
     * @param int   $depth   json_decode Parameter
     * @param int   $options json_decode Parameter
     *
     * @return array
     */
    public static function array_decode_json_recursive(array $current, $assoc = false, $depth = 512, $options = 0)
    {
        foreach ($current as $key => $value) {
            switch (true) {
                case is_array($value):
                    $current[$key] = self::array_decode_json_recursive($value, $assoc, $depth, $options);
                    break;
                case self::is_json($value, false, $assoc, $depth, $options):
                    $current[$key] = self::decode($value, $assoc, $depth, $options);
                    break;
                default:
                    $current[$key] = $value;
                    break;
            }
        }

        return $current;
    }
}
