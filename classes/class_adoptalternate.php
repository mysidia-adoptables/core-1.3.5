<?php

class AdoptAlternate extends Model
{
    protected $alid;
    protected $adopt;
    protected $image;
    protected $level;
    protected $item;
    protected $gender;
    protected $lastAlt;
    protected $chance;

    public function __construct($param)
    {
        $mysidia = Registry::get("mysidia");
        $row = is_object($param) ? $param : $mysidia->db->select("alternates", [], "alid='{$alid}'")->fetchObject();
        if (!is_object($row)) {
            throw new AlternateNotfoundException("The alternate form {$param} does not exist...");
        }
        foreach ($row as $key => $val) {
            $this->$key = $val;
        }
    }

    public function getALID()
    {
        return $this->alid;
    }

    public function getAdopt($fetchMode = "")
    {
        if ($fetchMode == Model::MODEL) {
            return new Adoptable($this->adopt);
        } else {
            return $this->adopt;
        }
    }

    public function getImage($fetchMode = "")
    {
        if ($fetchMode == Model::GUI) {
            return new Image($this->image);
        }
        return $this->image;
    }

    public function getLevel($fetchMode = "")
    {
        if ($fetchMode == Model::MODEL) {
            return new AdoptLevel($this->adopt, $this->level);
        } else {
            return $this->level;
        }
    }

    public function getItem()
    {
        return $this->item;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getLastAlt($fetchMode = "")
    {
        if ($fetchMode == Model::MODEL) {
            return new AdoptAlternate($this->lastAlt);
        }
        return $this->lastAlt;
    }

    public function getChance()
    {
        return $this->chance;
    }

    protected function save($field, $value)
    {
        $mysidia = Registry::get("mysidia");
        $mysidia->db->update("alternates", [$field => $value], "alid='{$this->alid}'");
    }
}
