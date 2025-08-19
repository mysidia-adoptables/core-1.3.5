<?php

use Resource\Native\Arrays;

class Tab
{
    private $name;
    private $alias;
    private $hide;
    private $index;
    private $last;

    public function __construct(private $num, $tabs, private $default = 1)
    {
        include_once("css/tabs.css");
        $this->name = new Arrays($this->num);
        $this->alias = new Arrays($this->num);
        $this->index = new Arrays($this->num);
        $this->last = new Arrays($this->num);

        $i = 0;
        $iterator = $tabs->iterator();
        while ($iterator->hasNext()) {
            $entry = $iterator->next();
            $this->name[$i] = (string)$entry->getKey();
            $this->alias[$i] = (string)$entry->getValue();
            $this->index[$i] = ($i == $this->default - 1) ? " class='current" : "";
            $this->index[$i] = ($i == $this->num - 1) ? " class='last" : $this->index[$i];
            $this->last[$i] = ($i == $this->num - 1) ? " last" : "";
            $i++;
        }
    }

    public function createtab()
    {
        $mysidia = Registry::get("mysidia");
        $document = $mysidia->frame->getDocument();
        if ($this->num < 2 or $this->num > 5) {
            throw new Exception("The number of tabs must be restricted between 2 to 5!", 272);
        }
        $init = "<div id='page-wrap'><div id='profile'><ul class='nav'>";
        for ($i = 0; $i < $this->num; $i++) {
            $init .= " <li class='nav{$i}{$this->last[$i]}'><a href='#{$this->alias[$i]}'{$this->index[$i]}'>{$this->name[$i]}</a></li>";
        }
        $init .= "</ul><div class='list-wrap'>";
        $document->addLangvar($init);
    }

    public function starttab($index)
    {
        $mysidia = Registry::get("mysidia");
        $document = $mysidia->frame->getDocument();
        $this->hide = ($index == $this->default - 1) ? "" : " class='hide";
        $header = "<ul id='{$this->alias[$index]}'{$this->hide}'>";
        $document->addLangvar($header);
    }

    public function endtab($index)
    {
        $mysidia = Registry::get("mysidia");
        $document = $mysidia->frame->getDocument();
        $footer = "</ul>";
        if ($index == $this->num - 1) {
            $footer .= "</div></div>";
        }
        $document->addLangvar($footer);
    }
}
