<?php defined('_JEXEC') || die('=;)');
/**
 * @package    GetJ2Rest
 * @subpackage REST.classes
 * @author     Nikolai Plath {@link https://github.com/elkuku}
 * @author     Created on 18-Aug-2012
 * @license    GNU/GPL
 */

/**
 * XML response class.
 */
final class RestResponseXml extends RestResponse
{
    /**
     * @var SimpleXMLElement
     */
    private $xml;

    public $debug = false;

    /**
     * Get the response mime type.
     *
     * @return string
     */
    public function getMimeType()
    {
        return 'application/xml';
    }

    /**
     * Convert to a string.
     *
     * @return string in XML format
     */
    public function __toString()
    {
        $class = ($this->debug) ? 'ResponseXmlDebug' : 'SimpleXMLElement';

        $this->xml = simplexml_load_string('<xml/>', $class);

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
                if(false == is_int($k))
                    $this->xml->addChild($k, $v);
            }
        }
    }

}

class ResponseXmlDebug extends SimpleXMLElement
{
    /**
     * Return a well-formed XML string based on SimpleXML element
     *
     * @param   integer  $level  The level within the document which informs the indentation.
     *
     * @return  string
     */
    public function asXML($level = 0)
    {
        $indent = "\t";
        $out = '';

        $out .= "\n".str_repeat($indent, $level);
        $out .= '<'.$this->getName();

        foreach($this->attributes() as $attr)
        {
            $out .= ' '.$attr->getName().'="'.htmlspecialchars((string)$attr, ENT_COMPAT, 'UTF-8').'"';
        }

        if(! count($this->children()) && ! (string)$this && '0' != (string)$this)
        {
            $out .= ' />';
        }
        else
        {
            if(count($this->children()))
            {
                $out .= '>';

                $level ++;

                foreach($this->children() as $child)
                {
                    $out .= $child->asXML($level);
                }

                $level --;

                $out .= "\n".str_repeat($indent, $level);

            }
            elseif((string)$this || 0 == (string)$this)
            {
                $out .= '>'.htmlspecialchars((string)$this, ENT_COMPAT, 'UTF-8');
            }

            $out .= '</'.$this->getName().'>';
        }

        return $out;
    }

}
