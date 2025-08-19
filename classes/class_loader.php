<?php

use Resource\Native\MysObject;

/**
 * The Loader Class, it is vital to class autoloading mechanism of this script.
 * It is capable of loading every Mysidia classes, while ignores third party classes.
 * @category Resource
 * @package Utility
 * @author Hall of Famer
 * @copyright Mysidia Adoptables Script
 * @link http://www.mysidiaadoptables.com
 * @since 1.3.2
 * @todo Not much at this point.
 *
 */

class Loader extends MysObject
{
    /**
     * The classes property, stores a list of classes already loaded.
     * @access protected
     * @var ArrayObject
    */
    protected $classes;

    /**
     * Constructor of Loader Class, it assigns $dir property and registers loader to PHP.
     * @param String  $dir
     * @access public
     * @return Void
     */
    public function __construct(/**
     * The dir property, defines relative directory for loading process.
     * @access protected
     */
        protected $dir
    ) {
        $this->classes = new ArrayObject();
        spl_autoload_register($this->load(...));
    }

    /**
     * The extract method, returns a list of registered autoloader methods.
     * @access public
     * @return Void
     */
    public function extract()
    {
        return spl_autoload_function();
    }

    /**
     * The getClasses method, returns a set of loaded classes.
     * @access public
     * @return ArrayObject
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * The load method, it is where class autoloading is handled.
     * @access public
     * @return Void
     */
    public function load($class)
    {
        if (str_contains((string) $class, "Smarty")) {
            return;
        }

        $this->classes->append($class);
        $class_name = strtolower((string) $class);
        $class_path = "classes/class_{$class_name}";
        if (str_contains((string) $class, "\\")) {
            $class_name = strtolower(str_replace("\\", "/", $class));
            $class_path = "classes/{$class_name}";
        } else {
            $view_path = str_replace("acp", "", "view/{$class_name}");
        }

        if (file_exists("{$this->dir}{$class_path}.php")) {
            include("{$this->dir}{$class_path}.php");
        } elseif (file_exists("{$view_path}.php")) {
            include("{$view_path}.php");
        } else {
            throw new Exception("Fatal Error: Class {$class} either does not exist, or has its include path misconfigured!");
        }
    }
}
