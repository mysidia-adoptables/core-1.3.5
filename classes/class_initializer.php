<?php

use Resource\Native\MysObject;
use Resource\Native\MysString;

/**
 * The Initializer Class, it is responsible for the basic bootstraping of the system.
 * It handles basic Loader, Registry, System Object creation, further operations are delegated to Mysidia System Class.
 * This is a final class, cannot be extended by any child classes.
 * @category Resource
 * @package Utility
 * @author Hall of Famer
 * @copyright Mysidia Adoptables Script
 * @link http://www.mysidiaadoptables.com
 * @since 1.3.3
 * @todo Not much at this point.
 * @final
 *
 */

final class Initializer extends MysObject
{
    /**
     * The dir property, defines relative directory for Bootstraping process.
     * @access private
     * @var MysString
    */
    private $dir;

    /**
     * The uri property, stores a reference of the URI from server variables.
     * @access private
     * @var MysString
    */
    private $uri;

    /**
     * Constructor of Initializer Class, it delegates to the method initialize() to complete the request.
     * @access public
     * @return Void
     */
    public function __construct()
    {
        $this->setURI();
        $this->setDir();
        $this->initialize();
    }

    /**
     * The getUri method, getter method for property $uri
     * @access public
     * @return Void
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * The setUri method, setter method for property $uri
     * The property is set upon Initializer object instantiation, cannot be called from external class.
     * @access private
     * @return Void
     */
    private function setUri()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
    }

    /**
     * The getDir method, getter method for property $dir
     * @access public
     * @return Void
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * The setDir method, setter method for property $dir
     * The property is set upon Initializer object instantiation, cannot be called from external class.
     * @access private
     * @return Void
     */
    private function setDir()
    {
        if (str_contains($this->uri, "admincp") or str_contains($this->uri, "install")) {
            $this->dir = "../";
        } else {
            $this->dir = "";
        }
    }

    /**
     * The initialize method, carries out the basic bootstraping steps.
     * It opens config file first, then include basic files and instantiate important objects.
     * @access private
     * @return Void
     */
    private function initialize()
    {
        $config = "{$this->dir}inc/config.php";
        if (!file_exists($config)) {
            exit("The file config.php cannot be found. If this is a new installation, please rename config_adopts.php to config.php and try again.");
        }
        require $config;
        if (!defined("DBHOST") || !defined("DBUSER")) {
            $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
            $redirectURL = $protocol . $_SERVER['HTTP_HOST'] . str_replace("index.php", "install", $_SERVER['PHP_SELF']);
            header("Location: {$redirectURL}");
            exit;
        }

        include("{$this->dir}functions/functions.php");
        include("{$this->dir}functions/functions_users.php");

        $loader = "{$this->dir}classes/class_loader.php";
        require $loader;
        $loader = new Loader($this->dir);

        $registry = Registry::getInstance();
        Registry::set(new MysString("loader"), $loader, true, true);

        $mysidia = new Mysidia();
        $router = new Router($this->uri);
        $router->route();
        $dispatcher = new Dispatcher($router);
        $dispatcher->dispatch();

        $wol = new Online();
        $wol->update();
        Registry::set(new MysString("wol"), $wol);
    }
}
