<?php

use Resource\Native\MysString;
use Resource\Collection\Collective;
use Resource\Collection\LinkedList;
use Resource\Collection\LinkedHashMap;

/**
 * The DropdownList Class, extends from abstract GUIContainer class.
 * It specifies a standard single-item dropdown list.
 * @category Resource
 * @package GUI
 * @author Hall of Famer
 * @copyright Mysidia Adoptables Script
 * @link http://www.mysidiaadoptables.com
 * @since 1.3.3
 * @todo Not much at this point.
 *
 */

class DropdownList extends GUIContainer
{
    /**
     * The autofocus property, checks if the input component is autofocused.
     * @access protected
     * @var Boolean
    */
    protected $autofocus = false;

    /**
     * The disabled property, checks if the input component is disabled.
     * @access protected
     * @var Boolean
    */
    protected $disabled = false;

    /**
     * Constructor of SelectList Class, which assigns basic property to this list
     * @param MysString  $name
     * @param ArrayList  $components
     * @param MysString  $identity
     * @param Event  $event
     * @access public
     * @return Void
     */
    public function __construct($name = "", $components = "", $identity = "", $event = "")
    {
        if (!empty($name)) {
            $this->setName($name);
            $this->setID($name);
        }
        if (!empty($identity)) {
            $this->select($identity);
        }
        if (!empty($event)) {
            $this->setEvent($event);
        }

        parent::__construct($components);
        $this->renderer = new ListRenderer($this);
    }

    /**
     * The isAutofocus method, getter method for property $autofocus.
     * @access public
     * @return Boolean
     */
    public function isAutofocus()
    {
        return $this->autofocus;
    }

    /**
     * The setAutofocus method, setter method for property $autofocus.
     * @param Boolean  $autofocus
     * @access public
     * @return Void
     */
    public function setAutofocus($autofocus = true)
    {
        $this->autofocus = $autofocus;
        $this->setAttributes("Autofocus");
    }

    /**
     * The isDisabled method, getter method for property $disabled.
     * @access public
     * @return Boolean
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * The setDisabled method, setter method for property $disabled.
     * @param Boolean  $disabled
     * @access public
     * @return Void
     */
    public function setDisabled($disabled = true)
    {
        $this->disabled = $disabled;
        $this->setAttributes("Disabled");
    }

    /**
     * The add method, sets an Option Object to a specific index.
     * @param Option|OptGroup  $option
     * @param int  $index
     * @access public
     * @return Void
     */
    #[\Override]
    public function add(GUIComponent $option, $index = -1)
    {
        if (!($option instanceof Option) and !($option instanceof OptGroup)) {
            throw new GUIException("Cannot add a non-option type component to dropdown list.");
        }
        parent::add($option, $index);
    }

    /**
     * The select method, determines which option in this list should be set selected.
     * @param MysString  $identity
     * @access public
     * @return Void
     */
    public function select($identity)
    {
        foreach ($this->components as $components) {
            if ($components->getValue() == $identity) {
                $components->setSelected(true);
            }
        }
    }

    /**
     * The fill method, fill in this dropdownlist with options from database starting at a given index.
     * To use it, you need PDO or MySQLi to fetch all rows with one or two properties to serve as collection list or map.
     * @param Collective $collection
     * @param MysString  $identity
     * @param Int  $index
     * @access public
     * @return Void
     */
    public function fill(Collective $collection, $identity = "", $index = -1)
    {
        if ($index != -1) {
            $this->currentIndex = $index;
        }
        if ($collection instanceof LinkedList) {
            $this->fillList($collection, $identity, $index);
        } elseif ($collection instanceof LinkedHashMap) {
            $this->fillMap($collection, $identity, $index);
        } else {
            throw new GUIException("Cannot fill option objects inside this dropdownlist");
        }
    }

    /**
     * The fillList method, fill in this dropdownlist with elements from a LinkedList.
     * @param LinkedList  $list
     * @param MysString  $identity
     * @param Int  $index
     * @access protected
     * @return Void
     */
    protected function fillList(LinkedList $list, $identity = "", $index = -1)
    {
        $iterator = $list->iterator();
        while ($iterator->hasNext()) {
            $field = (string)$iterator->next();
            $option = new Option($field, $field);
            if ($option->getValue() == $identity) {
                $option->setSelected(true);
            }
            $this->add($option, $index);
            if ($index != -1) {
                $index++;
            }
        }
    }

    /**
     * The fillMap method, fill in this dropdownlist with entries from a LinkedHashMap.
     * @param LinkedHashMap  $map
     * @param MysString  $identity
     * @param Int  $index
     * @access protected
     * @return Void
     */
    protected function fillMap(LinkedHashMap $map, $identity = "", $index = -1)
    {
        $iterator = $map->iterator();
        while ($iterator->hasNext()) {
            $field = $iterator->next();
            $option = new Option((string)$field->getKey(), (string)$field->getValue());
            if ($option->getValue() == $identity) {
                $option->setSelected(true);
            }
            $this->add($option, $index);
            if ($index != -1) {
                $index++;
            }
        }
    }

    /**
     * The fillReverse method, fill in this dropdownlist with entries from a LinkedHashMap reversely.
     * @param LinkedHashMap  $map
     * @param MysString  $identity
     * @param Int  $index
     * @access protected
     * @return Void
     */
    public function fillReverse(LinkedHashMap $map, $identity = "", $index = -1)
    {
        $iterator = $map->iterator();
        while ($iterator->hasNext()) {
            $field = $iterator->next();
            $option = new Option((string)$field->getValue(), (string)$field->getKey());
            if ($option->getValue() == $identity) {
                $option->setSelected(true);
            }
            $this->add($option, $index);
            if ($index != -1) {
                $index++;
            }
        }
    }

    /**
     * Magic method __toString for DropdownList class, it reveals that the object is a dropdown list.
     * @access public
     * @return MysString
     */
    #[\Override]
    public function __toString(): MysString
    {
        return (string) new MysString("This is an instance of Mysidia DropDownList class.");
    }
}
