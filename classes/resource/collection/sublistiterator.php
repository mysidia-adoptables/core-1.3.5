<?php

namespace Resource\Collection;

/**
 * The SubListIterator Class, extending from the ListIterator Class.
 * It defines a standard Iterator for SubList, it will come in handy.
 * @category Resource
 * @package Collection
 * @author Hall of Famer
 * @copyright Mysidia Adoptables Script
 * @link http://www.mysidiaadoptables.com
 * @since 1.3.4
 * @todo Not much at this point.
 *
 */

class SubListIterator extends ListIterator
{
    /**
     * The size property, it specifies the current size of the ArrayList.
     * @access private
     * @var Int
     */
    private $size;

    /**
     * Constructor of SubListIterator Class, it initializes the ListIterator with basic properties.
     * @param Int|Collective  $param
     * @access public
     * @return Void
     * @param int $fromIndex
     */
    public function __construct(Lists $list, /**
     * The offset property, it determines the offset between fromIndex and toIndex
     * @access private
     */
        private $offset, $toIndex)
    {
        $this->size = $toIndex - $this->offset;
        parent::__construct($this->offset, $list);
    }

    /**
     * The hasNext method, checks if the iterator has not reached the end of its iteration yet.
     * @access public
     * @return Boolean
     */
    #[\Override]
    public function hasNext()
    {
        return ($this->nextIndex() < $this->size);
    }

    /**
     * The hasPrevious method, checks if the list iterator has objects before its current index.
     * @access public
     * @return Boolean
     */
    #[\Override]
    public function hasPrevious()
    {
        return ($this->previousIndex() >= 0);
    }
}
