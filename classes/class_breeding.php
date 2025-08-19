<?php

use Resource\Native\MysObject;

final class Breeding extends MysObject
{
    private $offsprings;
    private $validator;

    public function __construct(private readonly OwnedAdoptable $female, private readonly OwnedAdoptable $male, private readonly BreedingSetting $settings)
    {
        $this->offsprings = new ArrayObject();
    }

    public function getValidator()
    {
        $mysidia = Registry::get("mysidia");
        if (func_num_args() == 0) {
            throw new InvalidActionException($mysidia->lang->global_action);
        }

        if (func_get_arg(0) == "all") {
            $validations = new ArrayObject(["class", "gender", "owner", "species", "interval", "level", "capacity", "number", "cost", "usergroup", "item", "chance"]);
        } else {
            $validations = new ArrayObject(func_get_args());
        }

        $this->validator = new BreedingValidator($this->female, $this->male, $this->settings, $validations);
        return $this->validator;
    }

    public function getBabySpecies()
    {
        $mysidia = Registry::get("mysidia");
        $female = $this->female->getType();
        $male = $this->male->getType();
        $parentList = "{$female}, {$male}";
        $parentList2 = "{$male}, {$female}";

        $stmt = $mysidia->db->select("breeding", ["bid"], "((mother ='{$female}' and father = '{$male}') or (mother ='{$female}' and father = '') or (mother ='' and father = '{$male}') or parent = '{$female}' or parent = '{$male}' or parent = '{$parentList}' or parent = '{$parentList2}') and level <= {$this->female->getCurrentLevel()} and available = 'yes'");
        if ($stmt->rowCount() == 0) {
            return;
        } else {
            $species = new ArrayObject();
            while ($bid = $stmt->fetchColumn()) {
                $adopt = new BreedAdoptable($bid);
                $species->append($adopt);
            }
            return $species;
        }
    }

    public function getBabyAdopts($species = "")
    {
        if ($this->settings->method == "heuristic" or !$species) {
            $this->heuristicBreed();
        } else {
            $this->advancedBreed($species);
        }
        return $this->offsprings;
    }

    private function heuristicBreed()
    {
        $choices = [$this->female->getType(), $this->male->getType()];
        $num = random_int(1, $this->settings->number);
        for ($i = 0; $i < $num; $i++) {
            $rand = random_int(0, 1);
            $this->offsprings->append(new Adoptable($choices[$rand]));
        }
    }

    private function advancedBreed($species)
    {
        $speciesMap = new ArrayObject();
        $probability = new Probability();

        foreach ($species as $breed) {
            $speciesMap->offsetSet($breed->getBreedID(), $breed);
            $probability->addEvent($breed->getBreedID(), $breed->getProbability());
        }

        $num = random_int(1, $this->settings->number);
        for ($i = 0; $i < $num; $i++) {
            $bid = $probability->randomEvent();
            $adopt = $speciesMap->offsetGet($bid);
            if ($this->getSurvival($adopt)) {
                $this->offsprings->append($adopt);
            }
        }
    }

    public function getSurvival(BreedAdoptable $adopt)
    {
        $rand = random_int(0, 99);
        if ($rand < $adopt->getSurvivalRate()) {
            return true;
        } else {
            return false;
        }
    }

    public function countOffsprings()
    {
        return $this->offsprings->count();
    }

    public function getOffsprings()
    {
        return $this->offsprings;
    }

    public function breed()
    {
        $mysidia = Registry::get("mysidia");
        foreach ($this->offsprings as $adopt) {
            $code = $adopt->getCode();
            $gender = $adopt->getGender();
            $mysidia->db->insert("owned_adoptables", ["aid" => null, "type" => $adopt->getType(), "name" => $adopt->getType(), "owner" => $mysidia->user->username, "currentlevel" => 0, "totalclicks" => 0, "code" => $code,
                                                           "imageurl" => null, "alternate" => 0, "tradestatus" => 'fortrade', "isfrozen" => 'no', "gender" => $gender, "offsprings" => 0, "lastbred" => 0]);
        }
        $this->validator->setStatus("complete");
    }
}
