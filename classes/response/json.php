<?php defined('_JEXEC') || die('=;)');
/**
 * @package    Pizzza
 * @subpackage REST.classes
 * @author     Nikolai Plath {@link https://github.com/elkuku}
 * @author     Created on 18-Aug-2012
 * @license    GNU/GPL
 */

/**
 * JSON response class.
 */
final class RestResponseJson extends RestResponse
{
    /**
     * Magic toString method for sending the response in JSON format.
     *
     * @return  string  The response in JSON format
     */
    public function __toString()
    {
        return json_encode(get_object_vars($this));
    }
}
