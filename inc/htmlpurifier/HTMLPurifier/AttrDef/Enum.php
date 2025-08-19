<?php

// Enum = Enumerated
/**
 * Validates a keyword against a list of valid values.
 * @warning The case-insensitive compare of this function uses PHP's
 *          built-in strtolower and ctype_lower functions, which may
 *          cause problems with international comparisons
 */
class HTMLPurifier_AttrDef_Enum extends HTMLPurifier_AttrDef
{
    /**
     * Lookup table of valid values.
     * @todo Make protected
     */
    public $valid_values   = []; // values according to W3C spec

    /**
     * @param $valid_values List of valid values
     * @param $case_sensitive Bool indicating whether or not case sensitive
     */
    public function __construct(
        $valid_values = [], /**
         * Bool indicating whether or not enumeration is case sensitive.
         * @note In general this is always case insensitive.
         */
        protected $case_sensitive = false
    ) {
        $this->valid_values = array_flip($valid_values);
    }

    public function validate($string, $config, $context)
    {
        $string = trim((string) $string);
        if (!$this->case_sensitive) {
            // we may want to do full case-insensitive libraries
            $string = ctype_lower($string) ? $string : strtolower($string);
        }
        $result = isset($this->valid_values[$string]);

        return $result ? $string : false;
    }

    /**
     * @param $string In form of comma-delimited list of case-insensitive
     *      valid values. Example: "foo,bar,baz". Prepend "s:" to make
     *      case sensitive
     */
    #[\Override]
    public function make($string)
    {
        if (strlen((string) $string) > 2 && $string[0] == 's' && $string[1] == ':') {
            $string = substr((string) $string, 2);
            $sensitive = true;
        } else {
            $sensitive = false;
        }
        $values = explode(',', (string) $string);
        return new HTMLPurifier_AttrDef_Enum($values, $sensitive);
    }

}

// vim: et sw=4 sts=4
