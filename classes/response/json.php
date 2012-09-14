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
final class RestResponseJson extends RestResponse
{
    /**
     * @var string
     */
    private $callback = '';

    /**
     * Constructor.
     *
     * @param object  $response  The Response data
     * @param string  $message   The main response message
     */
    public function __construct($response = null, $message = '')
    {
        parent::__construct($response, $message);

        $this->callback = JFactory::getApplication()->input->get('callback');
    }

    /**
     * Get the response mime type.
     *
     * @return string
     */
    public function getMimeType()
    {
        return ($this->callback)
            ? 'text/javascript'
            : 'application/x-json';
    }

    /**
     * Convert to a string.
     *
     * @return string in JSON format
     */
    public function __toString()
    {
        $temp = get_object_vars($this);

        $json = json_encode($temp);

        return ($this->callback)
            ? $this->callback.'('.(string)$json.');'
            : $json;
    }

}
