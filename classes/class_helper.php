<?php

use Resource\Native\MysObject;
use Resource\Native\MysString;

/**
 * The abstract Helper Class, extends from the root object class.
 * It defines an interface for helper classes, which must be extended by child helper classes.
 * @category Helper
 * @package Helper
 * @author Hall of Famer
 * @copyright Mysidia Adoptables Script
 * @link http://www.mysidiaadoptables.com
 * @since 1.3.4
 * @todo Not much at this point.
 * @abstract
 *
 */

abstract class Helper extends MysObject
{
    /**
     * Constructor of Helper Class, which simply serves as a marker for helper classes.
     * @access public
     * @return Void
     */
    public function __construct()
    {

    }

    /**
     * Magic method __toString for Helper class, it reveals that the class belong to helper package.
     * @access public
     * @return MysString
     */
    #[\Override]
    public function __toString(): MysString
    {
        return (string) new MysString("This is an instance of Mysidia Helper class.");
    }
}
