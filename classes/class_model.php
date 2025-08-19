<?php

use Resource\Native\MysObject;
use Resource\Native\MysString;

/**
 * The Abstract Model Class, extends from abstract object class.
 * It is parent to all model type classes, which stores domain object properties.
 * @category Model
 * @package Model
 * @author Hall of Famer
 * @copyright Mysidia Adoptables Script
 * @link http://www.mysidiaadoptables.com
 * @since 1.3.3
 * @todo Not much at this point.
 * @abstract
 *
 */

abstract class Model extends MysObject
{
    /**
     * OBJ constant, stores the fetch mode Object.
    */
    public const OBJ = "object";

    /**
     * MODEL constant, stores the fetch mode Model.
    */
    public const MODEL = "model";

    /**
     * GUI constant, stores the fetch mode GUI.
    */
    public const GUI = "gui";

    /**
     * INSERT constant, defines the assign mode Insert.
    */
    public const INSERT = "insert";

    /**
     * UPDATE constant, defines the assign mode Update.
    */
    public const UPDATE = "update";

    /**
     * DELETE constant, defines the assign mode Delete.
    */
    public const DELETE = "delete";

    /**
     * Constructor of Model Class, which simply serves as a marker for child classes.
     * @access public
     * @return Void
     */
    public function __construct()
    {

    }

    /**
     * Magic method __toString for Model class, it reveals that the class belong to model package.
     * @access public
     * @return MysString
     */
    public function __toString(): MysString
    {
        return (string) new MysString("This is an instance of Mysidia Model class.");
    }

    /**
     * Abstract method save for Model class, it must be implemented by child domain model classes.
     * @access protected
     * @abstract
     */
    abstract protected function save($field, $value);
}
