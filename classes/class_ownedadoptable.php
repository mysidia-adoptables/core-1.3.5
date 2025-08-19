<?php

class OwnedAdoptable extends Adoptable
{
    protected $aid;
    protected $name;
    protected $owner;
    protected $currentlevel;
    protected $totalclicks;
    protected $code;
    protected $imageurl;
    protected $alternate;
    protected $tradestatus;
    protected $isfrozen;
    protected $gender;
    protected $offsprings;
    protected $lastbred;

    protected $nextlevel;
    protected $voters;

    public function __construct($aid, $owner = "")
    {
        $mysidia = Registry::get("mysidia");
        $whereClause = "aid ='{$aid}'";
        if (!empty($owner)) {
            $whereClause .= " and owner = '{$owner}'";
        }
        $row = $mysidia->db->select("owned_adoptables", [], $whereClause)->fetchObject();
        if (!is_object($row)) {
            throw new AdoptNotfoundException("Adoptable ID {$aid} does not exist or does not belong to the owner specified...");
        }

        parent::__construct($row->type);
        foreach ($row as $key => $val) {
            $this->$key = $val;
        }
    }

    public function getAdoptID()
    {
        return $this->aid;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name, $assignMode = "")
    {
        if ($assignMode == Model::UPDATE) {
            $this->save("name", $name);
        }
        $this->name = $name;
    }

    public function getOwner($fetchMode = "")
    {
        if ($fetchMode == Model::MODEL) {
            return new Member($this->owner);
        } else {
            return $this->owner;
        }
    }

    public function setOwner($owner, $assignMode = "")
    {
        if ($assignMode == Model::UPDATE) {
            $this->save("owner", $owner);
        }
        $this->owner = $owner;
    }

    public function getCurrentLevel($fetchMode = "")
    {
        if ($fetchMode == Model::MODEL) {
            return new AdoptLevel($this->type, $this->currentlevel);
        } else {
            return $this->currentlevel;
        }
    }

    public function setCurrentLevel($level, $assignMode = "")
    {
        $this->currentlevel = $level;
        if ($assignMode == Model::UPDATE) {
            $this->save("currentlevel", $this->currentlevel);
            $this->updateAlternate($assignMode);
        }
    }

    public function getTotalClicks()
    {
        return $this->totalclicks;
    }

    public function setTotalClicks($clicks, $assignMode = "")
    {
        if ($assignMode == Model::UPDATE) {
            $this->save("totalclicks", $clicks);
        }
        $this->totalclicks = $clicks;
    }

    #[\Override]
    public function getCode()
    {
        return $this->code;
    }

    public function getImageURL($fetchMode = "")
    {
        if ($fetchMode == Model::GUI) {
            return new Image($this->imageurl);
        } else {
            return $this->imageurl;
        }
    }

    public function getAlternate()
    {
        if ($fetchMode == Model::MODEL) {
            return new AdoptAlternate($this->alternate);
        }
        return $this->alternate;
    }

    public function updateAlternate($assignMode = "")
    {
        $alternate = $this->generateAlternate();
        if ($alternate) {
            $this->imageurl = $alternate->getImage();
            $this->alternate = $alternate->getALID();
        } else {
            $this->imageurl = null;
            $this->alternate = 0;
        }

        if ($assignMode == Model::UPDATE) {
            $this->save("imageurl", $this->imageurl);
            $this->save("alternate", $this->alternate);
        }
    }

    public function getTradeStatus()
    {
        return $this->tradestatus;
    }

    public function setTradeStatus($status, $assignMode = "")
    {
        if ($assignMode == Model::UPDATE) {
            $this->save("tradestatus", $status);
        }
        $this->tradestatus = $status;
    }

    public function isFrozen()
    {
        return $this->isfrozen;
    }

    public function setFrozen($frozen = true, $assignMode = "")
    {
        if ($assignMode == Model::UPDATE) {
            $this->save("isfrozen", $frozen);
        }
        $this->isfrozen = $frozen;
    }

    #[\Override]
    public function getGender($fetchMode = "")
    {
        if ($fetchMode == Model::GUI) {
            return new Image("picuploads/{$this->gender}.png");
        } else {
            return $this->gender;
        }
    }

    public function getOffsprings()
    {
        return $this->offsprings;
    }

    public function setOffsprings($offsprings = 1, $assignMode = "")
    {
        $this->offsprings = $offsprings;
        if ($assignMode == Model::UPDATE) {
            $this->save("offsprings", $this->offsprings);
        }
    }

    public function getLastBred($fetchMode = "")
    {
        if ($fetchMode == Model::OBJ) {
            return new DateTime($this->lastbred);
        }
        return $this->lastbred;
    }

    public function setLastBred($lastBred = 0, $assignMode = "")
    {
        if ($assignMode == Model::UPDATE) {
            $this->save("lastbred", $lastBred);
        }
        $this->lastbred = $lastBred;
    }

    public function getImage($fetchMode = "")
    {
        if ($this->imageurl) {
            return $this->getImageUrl($fetchMode);
        }
        if ($this->currentlevel == 0) {
            return $this->getEggImage($fetchMode);
        }

        if ($this->alternate == 0) {
            $level = $this->getCurrentLevel("model");
            return $level->getPrimaryImage($fetchMode);
        } else {
            $alternate = $this->getAlternate();
            return $alternate->getImage($fetchMode);
        }
    }

    public function hasNextLevel()
    {
        try {
            $this->nextlevel = new AdoptLevel($this->type, $this->currentlevel + 1);
            return true;
        } catch (LevelNotfoundException) {
            return false;
        }
    }

    public function getNextLevel()
    {
        if (!$this->nextlevel) {
            return false;
        }
        return $this->nextlevel;
    }

    public function getLevelupClicks()
    {
        if (!$this->nextlevel) {
            return false;
        }
        return $this->nextlevel->getRequiredClicks() - $this->totalclicks;
    }

    public function generateAlternate()
    {
        if (!$this->canUseAlternate($this->currentlevel)) {
            return;
        }
        $alternateModels = $this->filterAlternates();
        $totalChance = 100;
        $currentNumber = 0;
        $winningNumber = random_int($currentNumber, $totalChance - 1);
        foreach ($alternateModels as $alternateModel) {
            $nextNumber = (int)$alternateModel->getChance() + $currentNumber;
            if ($winningNumber >= $currentNumber && $winningNumber < $nextNumber) {
                return $alternateModel;
            }
            $currentNumber = $nextNumber;
        }
    }

    private function filterAlternates()
    {
        $alternateModels = $this->getAlternatesForLevel($this->currentlevel);
        $filterGender = ($this->gender == "f") ? "female" : "male";
        $filterALID = $this->alternate;
        $filterClosure = (fn ($alternateModel) => !$alternateModel->getItem() && ($alternateModel->getGender() == "both" || $alternateModel->getGender() == $filterGender) && (!$alternateModel->getLastAlt() || $alternateModel->getLastAlt() == $filterALID));
        return array_filter((array)$alternateModels, $filterClosure);
    }

    public function getStats()
    {
        $mysidia = Registry::get("mysidia");
        $document = $mysidia->frame->getDocument();
        $stats = new Division("adoptstats");
        $stats->add(new Comment("<br><br><b>Total Clicks: {$this->totalclicks}"));
        $stats->add(new Comment("Gender: ", false));
        $stats->add(new Image("picuploads/{$this->gender}.png"));

        if ($this->hasNextLevel()) {
            $level = $this->getNextLevel();
            $levelupClicks = $this->getLevelupClicks();
            $nextLevel = $level->getLevel().$mysidia->lang->clicks.$levelupClicks;
        } else {
            $nextLevel = $mysidia->lang->maximum;
        }

        $adoptStats = "<br>Trade Status: {$this->tradestatus}<br>
				       Current Level: {$this->currentlevel}<br>Next Level: {$nextLevel}</b>";
        $stats->add(new Comment($adoptStats));
        return $stats;
    }

    public function hasVoter($user, $date = "")
    {
        if (!$date) {
            $date = new DateTime();
        }
        $mysidia = Registry::get("mysidia");

        if ($user instanceof Member) {
            $whereClause = "adoptableid='{$this->aid}' and username = '{$user->username}' and date = '{$date->format('Y-m-d')}'";
        } else {
            $ip = secure($_SERVER['REMOTE_ADDR']);
            $whereClause = "adoptableid='{$mysidia->input->get("aid")}' and ip = '{$ip}' and date = '{$date->format('Y-m-d')}'";
        }

        $void = $mysidia->db->select("vote_voters", ["void"], $whereClause)->fetchColumn();
        if (is_numeric($void)) {
            return true;
        } else {
            return false;
        }
    }

    #[\Override]
    protected function save($field, $value)
    {
        $mysidia = Registry::get("mysidia");
        $mysidia->db->update("owned_adoptables", [$field => $value], "aid='{$this->aid}'");
    }
}
