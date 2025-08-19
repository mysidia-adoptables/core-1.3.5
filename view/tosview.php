<?php

class TosView extends View
{
    #[\Override]
    public function index()
    {
        if (isset($this->flags)) {
            $this->redirect(3, "index");
        }
    }
}
