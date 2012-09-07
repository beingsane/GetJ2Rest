<?php defined('_JEXEC') || die('=;)');
/**
 * @package    GetJ2Rest
 * @subpackage REST.classes
 * @author     Nikolai Plath {@link https://github.com/elkuku}
 * @author     Created on 18-Aug-2012
 * @license    GNU/GPL
 */

class RestLegacy {}

//-- @todo Joomla 2.5/3 specific "fixes"
//-- @todo START
jimport('cms.version.version');

$jversion = new JVersion;

define('JVERSION', $jversion->getShortVersion());

if(version_compare(JVERSION, '3', '>'))
{
    // @todo.... what's the "new" way in J! 3 ??
    jimport('legacy.application.helper');
    jimport('legacy.application.application');
}

//-- @todo END
