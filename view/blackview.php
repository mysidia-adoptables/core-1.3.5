<?php

class BlankView extends View
{
    #[\Override]
    public function index()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;
    }
}
