<?php

/**
 * Validates a boolean attribute
 */
class HTMLPurifier_AttrDef_HTML_Bool extends HTMLPurifier_AttrDef
{
    public $minimized = true;

    public function __construct(protected $name = false)
    {
    }

    public function validate($string, $config, $context)
    {
        if (empty($string)) {
            return false;
        }
        return $this->name;
    }

    /**
     * @param $string Name of attribute
     */
    #[\Override]
    public function make($string)
    {
        return new HTMLPurifier_AttrDef_HTML_Bool($string);
    }

}

// vim: et sw=4 sts=4
