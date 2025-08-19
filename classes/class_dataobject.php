<?php

use Resource\Native\MysObject;
use Resource\Native\MysString;

/**
 * The DataObject Class, it is part of the utility package and extends from the Object Class.
 * It acts as a wrapper for PDO's returned objects, which cannot be used in Collections Framework.
 * @category Resource
 * @package Utility
 * @author Hall of Famer
 * @copyright Mysidia Adoptables Script
 * @link http://www.mysidiaadoptables.com
 * @since 1.3.4
 * @todo Not much at this moment
 */

class DataMysObject extends MysObject
{
    /**
     * The constructor for DataObject Class, it creates a DataObject object and initialize the context.
     * @param MysObject  $data
     * @access public
     * @return Void
     */
    public function __construct(
        /**
         * The data property, it stores the wrapped PDO object.
         * @access protected
         */
        protected $data = null
    ) {
    }

    /**
     * Magic method __call for DataObject class, it delegates all method calls to the inner object.
     * @access public
     * @return Mixed
     */
    public function __call($method, $param)
    {
        return $this->data->$method($param);
    }

    /**
     * The get method, getter method for property $object.
     * @access public
     * @return MysObject
     */
    public function get()
    {
        return $this->data;
    }

    /**
     * The set method, setter method for property $object.
     * @param MysObject  $data
     * @access public
     * @return Void
     */
    public function set($data = null)
    {
        $this->data = $data;
    }

    /**
     * Magic method __toString for DataObject class, it prints out the basic class information.
     * @access public
     * @return MysString
     */
    public function __toString(): MysString
    {
        return (string) new MysString("This is The DataObject Class.");
    }
}
