<?php

use Resource\Native\Objective;
use Resource\Native\MysString;

/**
 * The Template Class, extending from the parent Smarty class and implementing Objective Interface.
 * It handles Mysidia-specific template variables and files by offering unique functionality.
 * @category Resource
 * @package Core
 * @author Hall of Famer
 * @copyright Mysidia Adoptables Script
 * @link http://www.mysidiaadoptables.com
 * @since 1.3.3
 * @todo Not much at this point.
 *
 */

class Template extends Smarty implements Objective
{
    /**
     * The scriptRoot property, it defines the root directory used for loading scripts.
     * @access private
     * @var MysString
    */
    private $scriptRoot;

    /**
     * The tempRoot property, it defines the root directory used for loading template files.
     * @access private
     * @var MysString
    */
    private $tempRoot;

    /**
     * The temp property, it specifies the appropriate template folder where themes are stored.
     * @access private
     * @var MysString
    */
    private $temp;

    /**
     * The css property, it specifies the additional css folder to load.
     * @access private
     * @var MysString
    */
    private $css;

    /**
     * The js property, it specifies the additional javascript folder to load.
     * @access private
     * @var MysString
    */
    private $js;

    /**
     * Constructor of Template Class, it initializes basic template properties.
     * @param Path  $path
     * @access public
     * @return Void
     */
    public function __construct(Path $path)
    {
        parent::__construct();
        $this->scriptRoot = $path->getRoot();
        $this->tempRoot = $path->getTempRoot();
        $this->temp = "templates/";
        $this->css = "css";
        $this->js = "js";

        $this->locateDirectories();
        $this->setCacheOptions();
        $this->assignTemplateVars();
    }

    /**
     * The assignTemplateVars method, assigns core template parameters to display.
     * @access private
     * @return Void
     */
    private function assignTemplateVars()
    {
        $this->assign("version", Mysidia::version);
        $this->assign("root", $this->scriptRoot);
        $this->assign("home", $this->tempRoot);
        $this->assign("temp", $this->temp);
        $this->assign("css", $this->css);
        $this->assign("js", $this->js);
    }

    /**
     * The equals method, checks whether target object is equivalent to this template.
     * @param Objective  $object
     * @access public
     * @return Boolean
     */
    public function equals(Objective $object)
    {
        return ($this == $object);
    }

    /**
     * The getClassName method, returns class name of an instance.
     * The return value may differ depending on child classes.
     * @access public
     * @return MysString
     */
    public function getClassName()
    {
        return new MysString(static::class);
    }

    /**
     * The hashCode method, returns the hash code for the very Template.
     * @access public
     * @return Int
     */
    public function hashCode()
    {
        return hexdec(spl_object_hash($this));
    }

    /**
     * The locateDirectories method, locates the core directories for the Template.
     * @access private
     * @return Void
     */
    private function locateDirectories()
    {
        $this->setCompileDir("{$this->scriptRoot}{$this->temp}compile/");
        $this->setConfigDir("{$this->scriptRoot}{$this->temp}config/");
        $this->setCacheDir("{$this->scriptRoot}{$this->temp}cache/");
    }

    /**
     * The output method, shows the rendered html page to the screen.
     * @access public
     * @return Void
     */
    public function output()
    {
        $this->display("template.tpl");
    }

    /**
     * The render method, renders the frame object to display.
     * @access public
     * @return Void
     */
    public function render()
    {
        $frame = Registry::get("frame");
        $renders = $frame->render();
        $iterator = $renders->iterator();
        while ($iterator->hasNext()) {
            $entry = $iterator->next();
            $key = (string)$entry->getKey();
            $value = ($entry->getValue() instanceof Renderable) ?
                      $entry->getValue()->render() :
                      (string)$entry->getValue();
            $this->assign($key, $value);
        }
    }

    /**
     * The serialize method, serializes an object into string format.
     * A serialized string can be stored in Constants, Database and Sessions.
     * @access public
     * @return MysString
     */
    public function serialize()
    {
        return serialize($this);
    }

    /**
     * The setCacheOptions method, sets standard cache options.
     * @access private
     * @return Void
     */
    private function setCacheOptions()
    {
        $this->caching = false;
        $this->cache_lifetime = 1;
    }

    /**
     * The setTheme method, assigns the theme folder for site template.
     * @access public
     * @return Void
     */
    public function setTheme($theme)
    {
        $this->assign("theme", $theme);
        $this->setTemplateDir($this->scriptRoot.$this->temp.$theme);
    }

    /**
     * The unserialize method, decode a string to its object representation.
     * This method can be used to retrieve object info from Constants, Database and Sessions.
     * @param MysString  $string
     * @access public
     * @return MysString
     */
    public function unserialize($string)
    {
        return unserialize($string);
    }

    /**
     * Magic method __toString() for Template class, returns template information in detail.
     * @access public
     * @return MysString
     */
    public function __toString(): MysString
    {
        return (string) new MysString("This is an instance of the Mysidia Template class.");
    }
}
