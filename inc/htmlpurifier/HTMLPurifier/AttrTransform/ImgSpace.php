<?php

/**
 * Pre-transform that changes deprecated hspace and vspace attributes to CSS
 */
class HTMLPurifier_AttrTransform_ImgSpace extends HTMLPurifier_AttrTransform
{
    protected $css = [
        'hspace' => ['left', 'right'],
        'vspace' => ['top', 'bottom']
    ];

    public function __construct(protected $attr)
    {
        if (!isset($this->css[$this->attr])) {
            trigger_error(htmlspecialchars((string) $this->attr) . ' is not valid space attribute');
        }
    }

    public function transform($attr, $config, $context)
    {

        if (!isset($attr[$this->attr])) {
            return $attr;
        }

        $width = $this->confiscateAttr($attr, $this->attr);
        // some validation could happen here

        if (!isset($this->css[$this->attr])) {
            return $attr;
        }

        $style = '';
        foreach ($this->css[$this->attr] as $suffix) {
            $property = "margin-$suffix";
            $style .= "$property:{$width}px;";
        }

        $this->prependCSS($attr, $style);

        return $attr;

    }

}

// vim: et sw=4 sts=4
