<?php

use Resource\Native\MysString;
use Resource\Collection\HashSet;

/**
 * The abstract TableContainer Class, extends from abstract GUIContainer class.
 * It defines properties for all table type components/containers, but cannot be instantiated itself.
 * @category Resource
 * @package GUI
 * @author Hall of Famer
 * @copyright Mysidia Adoptables Script
 * @link http://www.mysidiaadoptables.com
 * @since 1.3.3
 * @todo Not much at this point.
 *
 */

abstract class TableContainer extends GUIContainer
{
    /**
     * The width property, it stores the width of the entire table.
     * @access protected
     * @var MysString
    */
    protected $width;

    /**
     * The tableAttributes property, determines the css attributes unique to table containers.
     * @access protected
     * @var MysString
    */
    protected $tableAttributes;

    /**
     * Constructor of Table Class, sets up basic form properties.
     * @param MysString  $name
     * @param MysString  $width
     * @param MysString  $event
     * @param ArrayObject  $components
     * @access publc
     * @return Void
     */
    public function __construct($name = "", $width = "", $event = "", $components = "")
    {
        parent::__construct($components);
        if (!empty($name)) {
            $this->setName($name);
            $this->setID($name);
        }
        if (!empty($width)) {
            $this->setWidth($width);
        }
        if (!empty($event)) {
            $this->setEvent($event);
        }
        $this->lineBreak = false;
        $this->renderer = new TableRenderer($this);
    }

    /**
     * The getWidth method, getter method for property $width.
     * @access public
     * @return MysString
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * The setWidth method, setter method for property $width.
     * @param Int|MysString  $width
     * @access public
     * @return Void
     */
    public function setWidth($width)
    {
        if (is_numeric($width)) {
            $this->width = "{$width}px";
        } else {
            $this->width = $width;
        }
        $this->setTableAttributes("Width");
    }

    /**
     * The getTableAttributes method, getter method for property $tableAttributes.
     * @access public
     * @return HashSet
     */
    public function getTableAttributes()
    {
        return $this->tableAttributes;
    }

    /**
     * The setTableAttributes method, setter method for property $tableAttributes.
     * @param MysString  $tableAttributes
     * @access public
     * @return Void
     */
    public function setTableAttributes($tableAttributes)
    {
        if (!$this->tableAttributes) {
            $this->tableAttributes = new HashSet();
        }
        $this->tableAttributes->add(new MysString($tableAttributes));
    }

    /**
     * Magic method __toString for TableContainer class, it reveals that the class is a Table Container.
     * @access public
     * @return MysString
     */
    public function __toString(): MysString
    {
        return (string) new MysString("This is The TableContainer class.");
    }
}
