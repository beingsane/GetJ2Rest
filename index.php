<?php
/**
 * @package    GetJ2Rest
 * @subpackage REST
 * @author     Nikolai Plath {@link https://github.com/elkuku}
 * @author     Created on 30-Aug-2012
 * @license    GNU/GPL
 */

// We are a valid Joomla entry point.
define('_JEXEC', 1);

// Setup the base path related constant - one level above the CMS root..
//-- Use the $_SERVER array to NOT follow symlinks..
define('JPATH_BASE', dirname(dirname($_SERVER['SCRIPT_FILENAME'])));

ini_set('display_errors', true); //@debug
error_reporting(- 1); //@debug

require JPATH_BASE.'/includes/defines.php';
require JPATH_BASE.'/libraries/import.php';

JLoader::registerPrefix('Rest', __DIR__.'/classes');

/**
 * Pizzza REST interface.
 *
 * @package Pizzza
 */
class GetJ2Rest extends JApplicationWeb
{
    /**
     * Overrides the parent doExecute method to run the web application.
     *
     * @throws RuntimeException
     *
     * @return  void
     */
    protected function doExecute()
    {
        // Legacy options for J 2.5/3 back and forth :P
        new RestLegacy;

        /* @var RestResponse $response */
        $response = new RestResponseJson;

        $this->loadDispatcher();

        try
        {
            $rest = RestRequestCall::parseCall();

            if('json' != $rest->format)
            {
                $className = 'RestResponse'.ucfirst($rest->format);

                if(false == class_exists($className))
                    throw new RuntimeException('Invalid format');

                $response = new $className;
            }

            RestLoginHelper::login();

            JPluginHelper::importPlugin('restapi', $rest->call);

            $pluginClass = 'PlgRestapi'.ucfirst($rest->call);

            if(false == class_exists($pluginClass))
                throw new RuntimeException('Plugin not found');

            $this->registerEvent('onRestCall'
                , new $pluginClass($this->dispatcher, array('params' => new JRegistry))
            );

            $pluginResult = $this->triggerEvent('onRestCall', $rest->commands);

            if(false == isset($pluginResult[0]))
                throw new RuntimeException('No plugin result - disabled ?', 66);

            $response->setData($pluginResult[0]);
        }
        catch(RestExceptionAuthentication $e)
        {
            $response->setMessage('Authentication failure')
                ->setStatus(4);

            $this->setHeader('status', 401, true);
        }
        catch(InvalidArgumentException $e)
        {
            $response->setMessage($e->getMessage())
                ->setStatus(5);

            $this->setHeader('status', 406, true);
        }

        $this->mimeType = $response->getMimeType();
        $this->setBody((string)$response);

        JApplication::getInstance('site')
            ->logout();
    }

    /**
     * This is used by Joomla!'s auth plugin to load the language....
     *
     * @return bool
     */
    public function isAdmin()
    {
        return false;
    }

    /**
     * This is used by the user/joomla plugin.
     */
    public function checkSession()
    {
        JApplication::getInstance('site')
            ->checkSession();
    }

    /**
     * This is used by the user/joomla plugin.
     */
    public function getClientId()
    {
        return JApplication::getInstance('site')
            ->getClientId();
    }
}

try
{
    $application = JApplicationWeb::getInstance('GetJ2Rest');

    JFactory::$application = $application;

    $application->execute();
}
catch(Exception $e)
{
    JApplication::getInstance('site')
        ->logout();

    echo new RestResponseJson($e);
}
