<?php

namespace Resource\Native;

use Exception;
use Resource\Exception\ClassCastException;

/**
 * The Double Class, extending from the abstract Number class.
 * This class serves as a wrapper class for primitive data type double.
 * It is a final class, no child class shall derive from Double.
 * @category Resource
 * @package Native
 * @author Hall of Famer
 * @copyright Mysidia Adoptables Script
 * @link http://www.mysidiaadoptables.com
 * @since 1.3.2
 * @todo Design the binary, octal, hex code conversion methods.
 * @final
 *
 */

final class Double extends Number
{
    /**
     * Size constant, specifies the size a double value occupies.
    */
    public const Size = 64;

    /**
     * Base constant, stores the base used for exponent.
    */
    public const Base = 10;

    /**
     * MinCoeff constant, specifies the coefficient for minimum exponent.
    */
    public const MinCoeff = 1.8;

    /**
     * MaxCoeff constant, specifies the coefficient for maximum exponent.
    */
    public const MaxCoeff = 4.9;

    /**
     * MinExp constant, defines the minimum allowable exponent.
    */
    public const MinExp = -324;

    /**
     * MaxExp constant, defines the maximum allowable exponent.
    */
    public const MaxExp = 308;


    /**
     * Constructor of Double Class, initializes the Double wrapper class.
     * If supplied argument is not a double, it will be converted to double primitive type.
     * @param Number  $num
     * @access public
     * @return Void
     */
    public function __construct($num)
    {
        parent::__construct($num);
        if (!is_double($num)) {
            $num = (float)$num;
        }
        $this->value = $num;
    }

    /**
     * The getExp method, gets the exponent of this number.
     * @access private
     * @return Int
     */
    private function getExp($num)
    {
        return (int)log10(abs($num));
    }

    /**
     * The getMax method, gets the maximum allowable number in Double class.
     * @access private
     * @return Double
     */
    private function getMax()
    {
        return (self::MaxCoeff * self::Base ** self::MaxExp);
    }

    /**
     * The getMin method, gets the minimum allowable number in Double class.
     * @access private
     * @return Double
     */
    private function getMin()
    {
        return (-1 * self::MaxCoeff * self::Base ** self::MaxExp);
    }

    /**
     * The toByte method, converts value and returns a Byte Object.
     * @access public
     * @return Byte
     */
    public function toByte()
    {
        if ($this->intValue() < Byte::MinValue or $this->intValue() > Byte::MaxValue) {
            throw new ClassCastException('Cannot convert to Byte type.');
        }
        return new Byte($this->value);
    }

    /**
     * The toShort method, converts value and returns a Short Object.
     * @access public
     * @return Short
     */
    public function toShort()
    {
        if ($this->intValue() < Short::MinValue or $this->intValue() > Short::MaxValue) {
            throw new ClassCastException('Cannot convert to Short type.');
        }
        return new Short($this->value);
    }

    /**
     * The toInteger method, converts value and returns an Integer Object.
     * @access public
     * @return Integer
     */
    public function toInteger()
    {
        if ($this->intValue() < Integer::MinValue or $this->intValue() > Integer::MaxValue) {
            throw new ClassCastException('Cannot convert to Integer type.');
        }
        return new Integer($this->value);
    }

    /**
     * The toLong method, converts value and returns a Long Object.
     * @access public
     * @return Long
     */
    public function toLong()
    {
        if ($this->intValue() < Long::MinValue or $this->intValue() > Long::MaxValue) {
            throw new ClassCastException('Cannot convert to Long type.');
        }
        return new Long($this->value);
    }

    /**
     * The toFloat method, converts value and returns a Float Object.
     * In Mysidia Adoptables, converting from double to float type is disabled.
     * @access public
     * @return Void
     */
    public function toFloat(): never
    {
        throw new ClassCastException('Casting from Double to Float is not allowed.');
    }

    /**
     * The verify method, validates the supplied argument to see if a Double object can be instantiated.
     * @param Number  $num
     * @access public
     * @return Boolean
     */
    public function verify($num)
    {
        if ($num > $this->getMax()) {
            throw new Exception('Supplied value cannot be greater than 4.9*10e+308 for Double type.');
        } elseif ($num < $this->getMin()) {
            throw new Exception('Supplied value cannot be smaller than -4.9*10e+308 for Double type.');
        } elseif ($this->getExp($num) < self::MinExp) {
            throw new Exception('Supplied value with exponent cannot be less than 1.8*10e-324 for Double type.');
        } else {
            return true;
        }
    }
}
