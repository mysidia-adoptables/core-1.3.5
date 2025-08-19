<?php

class TosController extends AppController
{
    public function index()
    {
        $mysidia = Registry::get("mysidia");
        try {
            $document = $mysidia->frame->getDocument("tos");
        } catch (PageNotFoundException) {
            $this->setFlags("error", "nonexist");
        }
    }
}
?>	