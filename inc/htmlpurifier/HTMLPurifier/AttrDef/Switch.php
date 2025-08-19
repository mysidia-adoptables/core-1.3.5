<?php

/**
 * Decorator that, depending on a token, switches between two definitions.
 */
class HTMLPurifier_AttrDef_Switch
{
    /**
     * @param string $tag Tag name to switch upon
     * @param HTMLPurifier_AttrDef $withTag Call if token matches tag
     * @param HTMLPurifier_AttrDef $withoutTag Call if token doesn't match, or there is no token
     */
    public function __construct(protected $tag, protected $withTag, protected $withoutTag)
    {
    }

    public function validate($string, $config, $context)
    {
        $token = $context->get('CurrentToken', true);
        if (!$token || $token->name !== $this->tag) {
            return $this->withoutTag->validate($string, $config, $context);
        } else {
            return $this->withTag->validate($string, $config, $context);
        }
    }

}

// vim: et sw=4 sts=4
