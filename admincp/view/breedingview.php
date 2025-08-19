<?php

use Resource\Native\String;
use Resource\Collection\LinkedHashMap;

class ACPBreedingView extends View
{
    #[\Override]
    public function index()
    {
        parent::index();
        $mysidia = Registry::get("mysidia");
        $stmt = $this->getField("stmt")->get();
        $document = $this->document;
        $fields = new LinkedHashMap();
        $fields->put(new String("bid"), null);
        $fields->put(new String("offspring"), null);
        $fields->put(new String("parent"), new String("getAdopt"));
        $fields->put(new String("mother"), new String("getAdopt"));
        $fields->put(new String("father"), new String("getAdopt"));
        $fields->put(new String("bid::edit"), new String("getEditLink"));
        $fields->put(new String("bid::delete"), new String("getDeleteLink"));

        $breedAdoptTable = new TableBuilder("breedadopt");
        $breedAdoptTable->setAlign(new Align("center", "middle"));
        $breedAdoptTable->buildHeaders("Breed ID", "Offspring", "Parents", "Mother", "Father", "Edit", "Delete");
        $breedAdoptTable->setHelper(new AdoptTableHelper());
        $breedAdoptTable->buildTable($stmt, $fields);
        $document->add($breedAdoptTable);

        $pagination = $this->getField("pagination");
        $document->addLangvar($pagination->showPage());
    }

    public function add()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;
        if ($mysidia->input->post("submit")) {
            $document->setTitle($this->lang->added_title);
            $document->addLangvar($this->lang->added);
            return;
        }

        $document->setTitle($this->lang->add_title);
        $document->addLangvar($this->lang->add);
        $breedAdoptForm = new FormBuilder("addform", "add", "post");
        $breedAdoptForm->buildComment("<u><strong>Create A New Baby Adoptable:</strong></u>")
                       ->buildComment("Baby Adoptable: ", false)->buildTextField("offspring")
                       ->buildComment("Parent Adoptable(s): ", false)->buildTextField("parent")
                       ->buildComment("<b>If both parents are specified in the above field, separate them by comma.</b>")
                       ->buildComment("Mother Adoptable: ", false)->buildTextField("mother")
                       ->buildComment("Father Adoptable: ", false)->buildTextField("father")
                       ->buildComment("<b>The two fields above should be left empty if the parent field is entered.</b>")
                       ->buildComment("Probability for Baby Adoptable to appear: ", false)->buildTextField("probability")
                       ->buildComment("<b>The total probability for all baby possible adoptables is normalized to 100, so this number can be any positive integers.</b>")
                       ->buildComment("Baby Adoptable Survival Rate(0-100 scale): ", false)->buildTextField("survival")
                       ->buildComment("Level Requirement: ", false)->buildTextField("level")
                       ->buildCheckBox(" Make this baby adopt available now.", "available", "yes")
                       ->buildButton("Create a Baby Adopt", "submit", "submit");
        $document->add($breedAdoptForm);
    }

    public function edit()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;
        if (!$mysidia->input->get("bid")) {
            $this->index();
            return;
        }

        if ($mysidia->input->post("submit")) {
            $document->setTitle($this->lang->edited_title);
            $document->addLangvar($this->lang->edited);
            return;
        }

        $breedAdopt = $this->getField("breedAdopt");
        $document->setTitle($this->lang->edit_title);
        $document->addLangvar($this->lang->edit);
        $breedAdoptForm = new FormBuilder("addform", "", "post");
        $breedAdoptForm->buildComment("<u><strong>Create A New Baby Adoptable:</strong></u>")
                       ->buildComment("Baby Adoptable: ", false)->buildTextField("offspring", $breedAdopt->getOffspring())
                       ->buildComment("Parent Adoptable(s): ", false)->buildTextField("parent", $breedAdopt->getParent())
                       ->buildComment("<b>If both parents are specified in the above field, separate them by comma.</b>")
                       ->buildComment("Mother Adoptable: ", false)->buildTextField("mother", $breedAdopt->getMother())
                       ->buildComment("Father Adoptable: ", false)->buildTextField("father", $breedAdopt->getFather())
                       ->buildComment("<b>The two fields above should be left empty if the parent field is entered.</b>")
                       ->buildComment("Probability for Baby Adoptable to appear: ", false)->buildTextField("probability", $breedAdopt->getProbability())
                       ->buildComment("<b>The total probability for all baby possible adoptables is normalized to 100, so this number can be any positive integers.</b>")
                       ->buildComment("Baby Adoptable Survival Rate(0-100 scale): ", false)->buildTextField("survival", $breedAdopt->getSurvivalRate())
                       ->buildComment("Level Requirement: ", false)->buildTextField("level", $breedAdopt->getRequiredLevel())
                       ->buildCheckBox(" Make this baby adopt available now.", "available", "yes", $breedAdopt->isAvailable())
                       ->buildButton("Update Baby Adopt", "submit", "submit");
        $document->add($breedAdoptForm);
    }

    public function delete()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;
        if (!$mysidia->input->get("bid")) {
            $this->index();
            return;
        }
        $document->setTitle($this->lang->delete_title);
        $document->addLangvar($this->lang->delete);
        header("Refresh:3; URL='../index'");
    }

    public function settings()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;
        if ($mysidia->input->post("submit")) {
            $document->setTitle($this->lang->settings_changed_title);
            $document->addLangvar($this->lang->settings_changed);
            return;
        }

        $breedingSettings = $this->getField("breedingSettings");
        $document->setTitle($this->lang->settings_title);
        $document->addLangvar($this->lang->settings);
        $settingsForm = new FormBuilder("settingsform", "settings", "post");
        $breedingSystem = new LinkedHashMap();
        $breedingSystem->put(new String("Enabled"), new String("enabled"));
        $breedingSystem->put(new String("Disabled"), new String("disabled"));
        $breedingMethod = new LinkedHashMap();
        $breedingMethod->put(new String("Heuristic"), new String("heuristic"));
        $breedingMethod->put(new String("Advanced"), new String("advanced"));

        $settingsForm->buildComment("Breeding System Enabled:   ", false)->buildRadioList("system", $breedingSystem, $breedingSettings->system)
                     ->buildComment("Breeding Method(heuristic or advanced):   ", false)->buildRadioList("method", $breedingMethod, $breedingSettings->method)
                     ->buildComment("Ineligible Species(separate by comma):   ", false)->buildTextField("species", ($breedingSettings->species) ? implode(",", $breedingSettings->species) : "")
                     ->buildComment("Interval/wait-time(days) between successive attempts:	 ", false)->buildTextField("interval", $breedingSettings->interval)
                     ->buildComment("Minimum Level Requirement:	 ", false)->buildTextField("level", $breedingSettings->level)
                     ->buildComment("Maximum Breeding Attempts for each adopt:	", false)->buildTextField("capacity", $breedingSettings->capacity)
                     ->buildComment("Maximum Number of Offsprings per Breeding attempt:   ", false)->buildTextField("number", $breedingSettings->number)
                     ->buildComment("Chance for successful Breeding attempt:   ", false)->buildTextField("chance", $breedingSettings->chance)
                     ->buildComment("Cost for each Breeding attempt:	 ", false)->buildTextField("cost", $breedingSettings->cost)
                     ->buildComment("Usergroup(s) permitted to breed(separate by comma):	", false)->buildTextField("usergroup", ($breedingSettings->usergroup == "all") ? $breedingSettings->usergroup : implode(",", $breedingSettings->usergroup))
                     ->buildComment("Item(s) required to breed(separate by comma):	", false)->buildTextField("item", ($breedingSettings->item) ? implode(",", $breedingSettings->item) : "")
                     ->buildButton("Change Breeding Settings", "submit", "submit");
        $document->add($settingsForm);
    }
}
