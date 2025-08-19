<?php

use Resource\Native\MysString;
use Resource\Collection\LinkedList;
use Resource\Collection\LinkedHashMap;

class ACPLevelView extends View
{
    public function add()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;

        if (!$mysidia->input->get("type") and !$mysidia->input->post("type")) {
            $document->setTitle($this->lang->add_title);
            $document->add(new Comment($this->lang->add));
            $typeForm = new FormBuilder("typeform", "add", "post");
            $typeForm->buildDropdownList("type", "AdoptTypeList")
                            ->buildButton("Select", "submit", "submit");
            $document->add($typeForm);
        } elseif ($mysidia->input->post("adoptiename")) {
            $document->setTitle($this->lang->added_title);
            $document->addLangvar($this->lang->added);
        } else {
            $settings = $this->getField("settings");
            $type = $this->getField("type")->getValue();
            $alt = $this->getField("alt")->getValue();
            $currentlevel = $this->getField("currentlevel")->getValue();
            $previouslevel = $this->getField("previouslevel")->getValue();
            $prevlevelclicks = $this->getField("prevlevelclicks")->getValue();
            $description = $this->getField("description")->getValue();

            $document->setTitle($this->lang->add_level);
            $document->add(new Comment($description));
            $levelForm = new FormBuilder("addform", "add/{$type}", "post");
            $levelForm->add(new Comment("Primary Image Url:<br>", true, "u"));
            for ($i = $currentlevel; $i < $settings->maximum + 1; $i++) {
                $levelForm->add(new Comment("lv.{$i}: ", false, "b"));
                $levelForm->add(new Comment(" Hosted Image: ", false));
                $primaryHosted = new TextField("primaryhosted[]");
                $primaryHosted->setLineBreak(false);
                $levelForm->add($primaryHosted);
                $levelForm->add(new Comment("  OR Select an Existing Image: ", false));
                $levelForm->buildDropdownList("primarylocal[]", "ImageList");
            }

            $altStatus = " (Alternate Images are <strong>{$alt}</strong> for this adoptable.)<br><br>";
            $levelForm->add(new Comment($altStatus));
            $levelForm->add(new Comment("Required Clicks: ", false, "u"));
            $levelForm->add(new Comment(" (How many clicks are required to reach this level?)<br><br>"));
            for ($i = $currentlevel; $i < $settings->maximum + 1; $i++) {
                if ($settings->method == "incremental") {
                    $reqclicks = $settings->clicks[$i];
                } else {
                    $reqclicks = ($i == 1) ? $settings->clicks[0] : $settings->clicks[0] * $settings->clicks[1] ** ($i - 1);
                }
                $levelForm->add(new Comment("lv.{$i}: ", false, "b"));
                $levelForm->add(new TextField("reqclicks[]", $reqclicks));
            }
            $levelForm->add(new Comment($this->lang->reqclicks_explain));

            $levelForm->add(new PasswordField("hidden", "isreward", "no"));
            $levelForm->add(new PasswordField("hidden", "rewardcode", ""));
            $levelForm->add(new PasswordField("hidden", "adoptiename", $type));
            $levelForm->add(new PasswordField("hidden", "currentlevel", $currentlevel));
            $levelForm->add(new PasswordField("hidden", "prevclicks", $prevlevelclicks));
            $levelForm->add(new Button("Create Level(s)", "submit", "submit"));
            $document->add($levelForm);
        }
    }

    public function edit()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;

        if (!(string)$mysidia->input->get("type") and !$mysidia->input->post("type")) {
            $document = $this->document;
            $document->setTitle($this->lang->manage_title);
            $document->add(new Comment($this->lang->manage));
            $typeForm = new FormBuilder("typeform", "", "post");
            $typeForm->buildDropdownList("type", "AdoptTypeList")->buildButton("Select", "submit", "submit");
            $document->add($typeForm);
        } elseif (!$mysidia->input->post("adoptiename")) {
            if (!is_numeric((string)$mysidia->input->get("level"))) {
                $eggimage = $this->getField("eggimage");
                $document->setTitle($this->lang->manage_level);
                $document->addLangvar($this->lang->manage_explain);

                $levelTable = new TableBuilder("level");
                $levelTable->setAlign(new Align("center", "middle"));
                $levelTable->buildHeaders("Level", "Image", "Clicks Required", "Reward Given", "Edit", "Delete");

                $eggCells = new LinkedList();
                $eggCells->add(new TCell(0));
                $eggCells->add(new TCell($eggimage));
                $eggCells->add(new TCell(0));
                $eggCells->add(new TCell(new Image("templates/icons/no.gif")));
                $eggCells->add(new TCell(new Image("templates/icons/no.gif")));
                $eggCells->add(new TCell(new Image("templates/icons/no.gif")));
                $levelTable->buildRow($eggCells);

                $stmt = $this->getField("stmt")->get();
                while ($level = $stmt->fetchObject()) {
                    $levelCells = new LinkedList();
                    $levelCells->add(new TCell($level->thisislevel));
                    $levelCells->add(new TCell(new Image($level->primaryimage)));
                    $levelCells->add(new TCell($level->requiredclicks));
                    $levelCells->add(($level->rewarduser == "yes") ? new TCell(new Image("templates/icons/yes.gif")) : new TCell(new Image("templates/icons/no.gif")));
                    $levelCells->add(new TCell(new Link("admincp/level/edit/{$mysidia->input->post("type")}/{$level->thisislevel}", new Image("templates/icons/cog.gif"))));
                    $levelCells->add(new TCell(new Link("admincp/level/delete/{$mysidia->input->post("type")}/{$level->thisislevel}", new Image("templates/icons/delete.gif"))));
                    $levelTable->buildRow($levelCells);
                }
                $document->add($levelTable);
            } else {
                $level = $this->getField("level")->get();
                if (str_contains((string) $level->primaryimage, "picuploads")) {
                    $primaryLocal = $level->primaryimage;
                } else {
                    $primaryHosted = $level->primaryimage;
                }
                $document->setTitle($this->lang->edit_title.$mysidia->input->get("type"));
                $document->addLangvar($this->lang->edit);

                $levelForm = new FormBuilder("editform", $mysidia->input->get("level"), "post");
                $levelForm->add(new Comment("<b><u>Primary Image for This Level:</u></b>"));
                $levelForm->add(new Image($level->primaryimage));
                $levelForm->add(new Comment("<br>Change to Hosted Image: ", false));
                $levelForm->add(new TextField("primaryhosted", $primaryHosted));
                $levelForm->add(new Comment("  OR Select an Existing Image: ", false));
                $levelForm->buildDropdownList("primarylocal", "ImageList", $primaryLocal);

                $levelForm->add(new Comment("Required Clicks", false, "b"));
                $levelForm->add(new TextField("reqclicks", $level->requiredclicks));
                $levelForm->add(new PasswordField("hidden", "reward", $level->rewarduser));
                $levelForm->add(new PasswordField("hidden", "promocode", $level->promocode));
                $levelForm->add(new PasswordField("hidden", "adoptiename", $mysidia->input->get("type")));
                $levelForm->add(new Button("Edit Level", "submit", "submit"));
                $document->add($levelForm);
            }
        } else {
            $document->setTitle($this->lang->edited_title);
            $document->addLangvar($this->lang->edited);
        }
    }

    public function delete()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;

        if (!(string)$mysidia->input->get("type") and !$mysidia->input->post("type")) {
            $this->edit();
        } elseif (!$mysidia->input->post("adoptiename")) {
            if (!is_numeric((string)$mysidia->input->get("level"))) {
                $this->edit();
            } else {
                $level = $this->getField("level")->get();
                $document->setTitle($this->lang->delete_title.$mysidia->input->get("type"));
                $document->addLangvar($this->lang->delete);
                $document->add(new Image("templates/icons/warning.gif"));
                $document->add(new Comment($this->lang->delete_explain));

                $levelForm = new FormBuilder("deleteform", $mysidia->input->get("level"), "post");
                $levelForm->add(new Comment("<b><u>Primary Image for This Level:</u></b>"));
                $levelForm->add(new Image($level->primaryimage));
                $levelForm->add(new Comment("<br>"));
                $levelForm->add(new Comment("<b><u>Alternate Image for This Level:</u></b>"));
                $levelForm->add(new Image($level->alternateimage));
                $levelForm->add(new Comment("<br>"));
                $levelForm->add(new PasswordField("hidden", "adoptiename", $mysidia->input->get("type")));
                $levelForm->add(new Button("Delete Level", "submit", "submit"));
                $document->add($levelForm);
            }
        } else {
            $document->setTitle($this->lang->edited_title);
            $document->addLangvar($this->lang->edited);
        }
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

        $document->setTitle($this->lang->settings_title);
        $document->addLangvar($this->lang->settings);
        $settingsForm = new FormBuilder("settingsform", "settings", "post");
        $levelSettings = $this->getField("levelSettings");
        $levelSystem = new LinkedHashMap();
        $levelSystem->put(new MysString("Enabled"), new MysString("enabled"));
        $levelSystem->put(new MysString("Disabled"), new MysString("disabled"));
        $levelMethod = new LinkedHashMap();
        $levelMethod->put(new MysString("Incremental"), new MysString("incremental"));
        $levelMethod->put(new MysString("Multiple"), new MysString("multiple"));
        $levelOwner = clone $levelSystem;

        $settingsForm->buildComment("Level-Clicks System Enabled:   ", false)->buildRadioList("system", $levelSystem, $levelSettings->system)
                     ->buildComment("Level-Clicks Mechanism:   ", false)->buildRadioList("method", $levelMethod, $levelSettings->method)
                     ->buildComment($this->lang->method_explain)
                     ->buildComment("Max-Level allowed for all Species:	 ", false)->buildTextField("maximum", $levelSettings->maximum)
                     ->buildComment("Required Clicks Patterns:   ", false)->buildTextField("clicks", ($levelSettings->clicks) ? implode(",", $levelSettings->clicks) : "")
                     ->buildComment($this->lang->clicks_explain)
                     ->buildComment("Maximum Number of adoptables allowed for daily clicks:   ", false)->buildTextField("number", $levelSettings->number)
                     ->buildComment($this->lang->number_explain)
                     ->buildComment("Min and Max Money Reward for clicking adoptables(separate by comma):	", false)->buildTextField("reward", implode(",", $levelSettings->reward))
                     ->buildComment("Allow Users to click their own pets:	", false)->buildRadioList("owner", $levelOwner, $levelSettings->owner)
                     ->buildComment($this->lang->owner_explain)
                     ->buildButton("Change Level Settings", "submit", "submit");
        $document->add($settingsForm);
    }

    public function daycare()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;
        if ($mysidia->input->post("submit")) {
            $document->setTitle($this->lang->daycare_changed_title);
            $document->addLangvar($this->lang->daycare_changed);
            return;
        }

        $document->setTitle($this->lang->daycare_title);
        $document->addLangvar($this->lang->daycare, true);
        $daycareSettings = $this->getField("daycareSettings");
        $daycareSystem = new LinkedHashMap();
        $daycareSystem->put(new MysString("Enabled"), new MysString("enabled"));
        $daycareSystem->put(new MysString("Disabled"), new MysString("disabled"));
        $daycareDisplay = new LinkedHashMap();
        $daycareDisplay->put(new MysString("All"), new MysString("all"));
        $daycareDisplay->put(new MysString("Random"), new MysString("random"));

        $daycareForm = new FormBuilder("daycareform", "daycare", "post");
        $daycareForm->buildComment("Daycare Center Enabled:   ", false)->buildRadioList("system", $daycareSystem, $daycareSettings->system)
                    ->buildComment("Daycare Display Method:   ", false)->buildRadioList("display", $daycareDisplay, $daycareSettings->display)
                    ->buildComment("Maximum Pets per page:   ", false)->buildTextField("number", $daycareSettings->number)
                    ->buildComment("Maximum Columns per Row:	 ", false)->buildTextField("columns", $daycareSettings->columns)
                    ->buildComment("Max-level Allowed:	 ", false)->buildTextField("level", $daycareSettings->level)
                    ->buildComment("Ineligible Species:   ", false)->buildTextField("species", ($daycareSettings->species) ? implode(',', $daycareSettings->species) : $daycareSettings->species)
                    ->buildComment("Stats/Info Display:	 ", false)->buildTextField("info", ($daycareSettings->info) ? implode(',', $daycareSettings->info) : $daycareSettings->info)
                    ->buildComment("<b>The following six stats are available: Type, Name, Owner, CurrentLevel, TotalClicks, Gender. <br>Note this is case sensitive!</b>")
                    ->buildComment("Show User's own Adopts:	 ", false)->buildTextField("owned", $daycareSettings->owned)
                    ->buildButton("Change Daycare Settings", "submit", "submit");
        $document->add($daycareForm);
    }
}
