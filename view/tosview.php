<?php

class TosView extends View
{
    public function index()
    {
        if (isset($this->flags)) {
            $this->redirect(3, "index");
        }
    }
}
