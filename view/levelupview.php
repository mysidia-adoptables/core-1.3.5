<?php

use Resource\Collection\ArrayList;
use Resource\Utility\Curl;

class LevelupView extends View
{
    public function click()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;
        $adopt = $this->getField("adopt");
        $reward = $this->getField("reward")->getValue();
        $document->setTitle("{$this->lang->gave} {$adopt->getName()} one {$this->lang->unit}");

        $image = $adopt->getImage("gui");
        $image->setLineBreak(true);
        $summary = new Division();
        $summary->setAlign(new Align("center"));
        $summary->add($image);
        $summary->add(new Comment("{$this->lang->gave}{$adopt->getName()} one {$this->lang->unit}."));
        $summary->add(new Comment($this->lang->encourage));
        if ($mysidia->user->isloggedin) {
            $summary->add(new Comment("<br> You have earned {$reward} {$mysidia->settings->cost} for leveling up this adoptable. "));
            $summary->add(new Comment("You now have {$mysidia->user->getcash()} {$mysidia->settings->cost}"));
        }
        $document->add($summary);
    }

    public function siggy()
    {

    }

    public function daycare()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;
        $document->setTitle($this->lang->daycare_title);
        $document->addLangvar($this->lang->daycare, true);

        $daycare = $this->getField("daycare");
        $adopts = $daycare->getAdopts();
        $daycareTable = new Table("daycare", "", false);
        $daycareTable->setBordered(false);
        $total = $daycare->getTotalAdopts();
        $index = 0;

        for ($row = 0; $row < $daycare->getTotalRows(); $row++) {
            $daycareRow = new TRow("row{$row}");
            for ($column = 0; $column < $daycare->getTotalColumns(); $column++) {
                $adopt = new OwnedAdoptable($adopts[$index]);
                $cell = new ArrayList();
                $cell->add(new Link("levelup/click/{$adopt->getAdoptID()}", $adopt->getImage("gui"), true));
                $cell->add(new Comment($daycare->getStats($adopt)));
                $daycareCell = new TCell($cell, "cell{$index}");
                $daycareCell->setAlign(new Align("center", "center"));
                $daycareRow->add($daycareCell);
                $index++;
                if ($index == $total) {
                    break;
                }
            }
            $daycareTable->add($daycareRow);
        }

        $document->add($daycareTable);
        if ($pagination = $daycare->getPagination()) {
            $document->addLangvar($pagination->showPage());
        }
    }
}
