<?php

/**
 * The Abstract GUIAccessory Class, extends from abstract GUIComponent class.
 * It is parent to all GUI Accessories type classes, but cannot be instantiated itself.
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

abstract class GUIAccessory extends GUIComponent
{
    /**
     * The containers property, specifies which containers can hold this accessory object.
     * @access protected
     * @var Array
    */
    protected $containers = [];

    /**
     * Constructor of GUIAccessory Class, assigns the proper renderer object.
     * @access public
     * @return Void
     */
    public function __construct($id)
    {
        if (!empty($id)) {
            $this->setID($id);
        }
        $this->setLineBreak(false);
        $this->renderer = new AccessoryRenderer($this);
    }

    /**
     * The getContainers method, getter method for property $containers
     * @access public
     * @return Array
     */
    public function getContainers()
    {
        return $this->containers;
    }

    /**
     * Magic method __toString for GUIAccessory class, it reveals that the class is an assessory type class.
     * @access public
     * @return String
     */
    #[\Override]
    public function __toString(): string
    {
        return (string) new String("This is the GUIAccessory Class.");
    }
}
