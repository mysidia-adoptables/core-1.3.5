<?php

use Resource\Native\MysObject;

abstract class MessageContainer extends MysObject implements Container
{
    // The abstract MessageContainer class

    public function format($text)
    {
        $text = html_entity_decode((string) $text);
        $text = str_replace("\r\n", "", $text);
        $text = stripslashes($text);
        return $text;
    }

    public function getcreator()
    {
        // Will be implemented in future
    }

    abstract public function display();
    abstract public function post();
}
