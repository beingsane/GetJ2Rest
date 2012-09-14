<?php defined('_JEXEC') || die('=;)');
/**
 * @package    GetJ2Rest
 * @subpackage REST.classes
 * @author     Nikolai Plath {@link https://github.com/elkuku}
 * @author     Created on 18-Aug-2012
 * @license    GNU/GPL
 */

/**
 * JSON response class.
 */
final class RestResponseDump extends RestResponse
{
    /**
     * Get the response mime type.
     *
     * @return string
     */
    public function getMimeType()
    {
        return 'text/plain';
    }

    /**
     * Convert to a string.
     *
     * @return string in JSON format
     */
    public function __toString()
    {
        $s = array();

        $s[] = 'Status: '.$this->status;
        $s[] = 'Message: "'.$this->message.'"';

        $s[] = 'Data: '.var_export($this->data, true);

        return implode("\n", $s);
    }

}
