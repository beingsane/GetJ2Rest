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
final class RestResponseXml extends RestResponse
{
    /**
     * @var SimpleXMLElement
     */
    private $xml;

    public function __toString()
    {
        $this->xml = simplexml_load_string('<xml/>');

        $this->dataToXml($this->data);

        $this->xml->addChild('status', $this->status);
        $this->xml->addChild('message', $this->message);

        return $this->xml->asXML();
    }

    private function dataToXml($data = null, SimpleXMLElement $xml = null)
    {
        if(is_null($data))
            return;

        foreach($data as $k => $v)
        {
            if(is_array($v) || is_object($v))
            {
                $xmlChild = (is_null($xml))
                    ? $this->xml->addChild($k)
                    : $xml->addChild($k);

                $this->dataToXml($v, $xmlChild);
            }
            else
            {
                $this->xml->addChild($k, $v);
            }
        }
    }
}
