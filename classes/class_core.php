<?php

use Resource\Native\MysObject;
use Resource\Native\MysString;

/**
 * The Abstract Core Class, extends from abstract object class.
 * It is parent to all core system classes, but cannot be instantiated itself.
 * @category Resource
 * @package Core
 * @author Hall of Famer
 * @copyright Mysidia Adoptables Script
 * @link http://www.mysidiaadoptables.com
 * @since 1.3.2
 * @todo Not much at this point.
 * @abstract
 *
 */

abstract class Core extends MysObject
{
    /**
     * Constructor of Core Class, which simply serves as a marker for child classes.
     * @access public
     * @return Void
     */
    public function __construct()
    {

    }

    /**
     * Magic method __toString for Core class, it reveals that the class belong to core package.
     * @access public
     * @return MysString
     */
    public function __toString(): MysString
    {
        return (string) new MysString("This is an instance of Mysidia Core class.");
    }
}
