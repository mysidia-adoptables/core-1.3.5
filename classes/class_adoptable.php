<?php

use Resource\Native\MysString;

class Adoptable extends Model
{
    protected $id;
    protected $type;
    protected $class;
    protected $description;
    protected $eggimage;
    protected $whenisavail;
    protected $alternates;
    protected $altoutlevel;
    protected $shop;
    protected $cost;

    protected $conditions;
    protected $levels;
    protected $alternateModels;

    public function __construct($adoptinfo)
    {
        $mysidia = Registry::get("mysidia");
        if ($adoptinfo instanceof MysString) {
            $adoptinfo = $adoptinfo->getValue();
        }
        $whereClause = (is_numeric($adoptinfo)) ? "id ='{$adoptinfo}'" : "type ='{$adoptinfo}'";
        $row = $mysidia->db->select("adoptables", [], $whereClause)->fetchObject();
        if (!is_object($row)) {
            throw new AdoptNotfoundException("Adoptable {$adoptinfo} does not exist...");
        }
        foreach ($row as $key => $val) {
            $this->$key = $val;
        }
    }

    public function getID()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    #[\Override]
    public function getClass()
    {
        return $this->class;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getEggImage($fetchMode = "")
    {
        if ($fetchMode == Model::GUI) {
            return new Image($this->eggimage);
        }
        return $this->eggimage;
    }

    public function getWhenAvailable()
    {
        return $this->whenisavail;
    }

    public function hasAlternates()
    {
        return $this->alternates;
    }

    public function getAltLevel()
    {
        return $this->altoutlevel;
    }

    public function getShop($fetchMode = "")
    {
        if ($fetchMode == Model::MODEL) {
            return new AdoptShop($this->shop);
        } else {
            return $this->shop;
        }
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function getConditions()
    {
        if (!$this->conditions) {
            $this->conditions = new AdoptConditions($this);
        }
        return $this->conditions;
    }

    public function getLevel($level)
    {
        if (!$this->levels) {
            return new AdoptLevel($this->type, $level);
        }
        return $this->levels[$level];
    }

    public function getLevels()
    {
        if (!$this->levels) {
            $mysidia = Registry::get("mysidia");
            $this->levels = new ArrayObject();
            $num = $mysidia->db->select("levels", ["thisislevel"], "adoptiename='{$this->type}'")->rowCount();
            for ($i = 0; $i <= $num; $i++) {
                $this->levels->append(new AdoptLevel($this->type, $i));
            }
        }
        return $this->levels;
    }

    public function getAlternatesForLevel($level)
    {
        if (!$this->alternateModels) {
            $mysidia = Registry::get("mysidia");
            $this->alternateModels = new ArrayObject();
            $stmt = $mysidia->db->select("alternates", [], "adopt='{$this->type}' AND level='{$level}'");
            while ($alternate = $stmt->fetchObject()) {
                $this->alternateModels->append(new AdoptAlternate($alternate));
            }
        }
        return $this->alternateModels;
    }

    protected function canUseAlternate($level)
    {
        if ($this->alternates == "enabled" and $level >= $this->altoutlevel) {
            return true;
        }
        if ($this->getAlternatesForLevel($level)->count() > 0) {
            return true;
        }
        return false;
    }

    public function getMaxLevel()
    {
        return $this->levels->count();
    }

    public function getCode()
    {
        return codegen(10, 0);
    }

    public function getGender()
    {
        $genders = ['f', 'm'];
        $rand = random_int(0, 1);
        return $genders[$rand];
    }

    protected function save($field, $value)
    {
        $mysidia = Registry::get("mysidia");
        $mysidia->db->update("adoptables", [$field => $value], "id='{$this->id}'");
    }
}
