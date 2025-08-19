<?php

use Resource\Native\MysObject;
use Resource\Native\MysString;
use Resource\Collection\HashSet;

/**
 * The Abstract GUI Class, extends from abstract Object class.
 * It is parent to all Mysidia GUI classes, but cannot be instantiated itself.
 * @category Resource
 * @package GUI
 * @author Hall of Famer
 * @copyright Mysidia Adoptables Script
 * @link http://www.mysidiaadoptables.com
 * @since 1.3.3
 * @todo Not much at this point.
 * @abstract
 *
 */

abstract class GUI extends MysObject
{
    /**
     * The id property, specifies the id of this component.
     * @access protected
     * @var MysString
    */
    protected $id;

    /**
     * The class property, specifies the class of this component.
     * @access protected
     * @var MysString
    */
    protected $class;

    /**
     * The event property, it holds information for javascript events.
     * @access protected
     * @var MysString
    */
    protected $event;

    /**
     * The attributes property, which stores a set of attributes pending to render.
     * @access protected
     * @var HashSet
    */
    protected $attributes;

    /**
     * The renderer property, which stores a reference to the render object.
     * @access protected
     * @var Renderer
    */
    protected $renderer;

    /**
     * The getID method, getter method for property $id.
     * @access public
     * @return MysString
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * The setID method, setter method for property $id.
     * @param MysString  $id
     * @access public
     * @return Void
     */
    public function setID($id)
    {
        $this->id = $id;
        $this->setAttributes("ID");
    }

    /**
     * The getClass method, getter method for property $class.
     * @access public
     * @return MysString
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * The setClass method, setter method for property $class.
     * @param MysString  $class
     * @access public
     * @return Void
     */
    public function setClass($class)
    {
        $this->class = $class;
        $this->setAttributes("Class");
    }

    /**
     * The getEvent method, getter method for property $event.
     * @access public
     * @return MysString
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * The setEvent method, setter method for property $event.
     * @param MysString  $event
     * @access public
     * @return Void
     */
    public function setEvent($event)
    {
        $this->event = $event;
        $this->setAttributes("Event");
    }

    /**
     * The getAttributes method, getter method for property $attributes.
     * @access public
     * @return HashSet
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * The setAttributes method, setter method for property $attributes.
     * This method is reserved for the GUI class to use only.
     * @param MysString  $attributes
     * @access public
     * @return Void
     */
    public function setAttributes($attributes)
    {
        if (!$this->attributes) {
            $this->attributes = new HashSet();
        }
        $this->attributes->add(new MysString($attributes));
    }

    /**
     * The getRenderer method, getter method for property $renderer.
     * @access public
     * @return Renderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * The setRenderer method, setter method for property $renderer.
     * @param Renderer  $renderer
     * @access public
     * @return Void
     */
    public function setRenderer(GUIRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Magic method __toString for GUI class, it reveals that the class belong to GUI package.
     * @access public
     * @return MysString
     */
    public function __toString(): MysString
    {
        return (string) new MysString("This is The base GUI Class.");
    }
}
