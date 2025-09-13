<?php

namespace Resource\Collection;

use Resource\Native\Objective;
use Resource\Exception\IllegalStateException;
use Resource\Exception\NosuchElementException;

/**
 * The abstract TreeMapIterator Class, extending from the abstract CollectionIterator Class.
 * It defines a base tree map iterator, it must be extended by subclasses.
 * @category Resource
 * @package Collection
 * @author Hall of Famer
 * @copyright Mysidia Adoptables Script
 * @link http://www.mysidiaadoptables.com
 * @since 1.3.4
 * @todo Not much at this point.
 * @abstract
 *
 */

abstract class TreeMapIterator extends CollectionIterator
{
    /**
     * The current property, it specifies the current Entry to return.
     * @access private
     * @var TreeMapEntry
    */
    private $current;

    /**
     * Constructor of TreeMapIterator Class, initializes basic properties for the iterator.
     * @param TreeMap  $map
     * @param MapEntry $next
     * @access public
     * @return Void
     */
    public function __construct(
        /**
         * The map property, it stores a reference to the TreeMap object.
         * @access private
         */
        private readonly TreeMap $map,
        /**
         * The next property, it defines the next Entry in iteration.
         * @access private
         */
        private MapEntry $next
    ) {
    }

    /**
     * The current method, returns the current entry in the iterator.
     * @access public
     * @return Entry
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * The hasNext method, checks if the iterator has next entry.
     * This is a final method, and thus can not be overriden by child class.
     * @access public
     * @return Entry
     * @final
     */
    final public function hasNext()
    {
        return ($this->next != null);
    }

    /**
     * The nextEntry method, returns the next entry in iteration.
     * This is a final method, and thus can not be overriden by child class.
     * @access public
     * @return Entry
     * @final
     */
    final public function nextEntry()
    {
        $entry = $this->next;
        if ($entry == null) {
            throw new NosuchElementException();
        }
        $this->next = $this->map->successor($entry);
        $this->current = $entry;
        return $entry;
    }

    /**
     * The prevEntry method, returns the previous entry in iteration.
     * This is a final method, and thus can not be overriden by child class.
     * @access public
     * @return Entry
     * @final
     */
    final public function prevEntry()
    {
        $entry = $this->next;
        if ($entry == null) {
            throw new NosuchElementException();
        }
        $this->next = $this->map->predecessor($entry);
        $this->current = $entry;
        return $entry;
    }

    /**
     * The remove method, removes from the underlying value associated with the current key in iteration.
     * @access public
     * @return Void
     */
    public function remove()
    {
        if ($this->current == null) {
            throw new IllegalStateException();
        }
        if ($this->current->getLeft() != null and $this->current->getRight() != null) {
            $this->next = $this->current;
        }
        $this->map->deleteEntry($this->current);
        $this->current = null;
    }
}
