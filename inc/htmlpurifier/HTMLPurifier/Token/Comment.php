<?php

/**
 * Concrete comment token class. Generally will be ignored.
 */
class HTMLPurifier_Token_Comment extends HTMLPurifier_Token
{
    /**< Character data within comment. */
    public $is_whitespace = true;
    /**
     * Transparent constructor.
     *
     * @param $data String comment data.
     */
    public function __construct(public $data, $line = null, $col = null)
    {
        $this->line = $line;
        $this->col  = $col;
    }
}

// vim: et sw=4 sts=4
