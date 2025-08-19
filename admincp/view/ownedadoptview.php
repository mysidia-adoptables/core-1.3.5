<?php

use Resource\Native\MysString;
use Resource\Collection\LinkedHashMap;

class ACPOwnedadoptView extends View
{
    public function index()
    {
        parent::index();
        $mysidia = Registry::get("mysidia");
        $stmt = $this->getField("stmt")->get();
        $document = $this->document;

        $fields = new LinkedHashMap();
        $fields->put(new MysString("aid"), null);
        $fields->put(new MysString("type"), null);
        $fields->put(new MysString("name"), null);
        $fields->put(new MysString("owner"), null);
        $fields->put(new MysString("gender"), new MysString("getGenderImage"));
        $fields->put(new MysString("aid::edit"), new MysString("getEditLink"));
        $fields->put(new MysString("aid::delete"), new MysString("getDeleteLink"));

        $ownedAdoptTable = new TableBuilder("ownedadopt");
        $ownedAdoptTable->setAlign(new Align("center", "middle"));
        $ownedAdoptTable->buildHeaders("ID", "Type", "Name", "Owner", "Gender", "Edit", "Delete");
        $ownedAdoptTable->setHelper(new AdoptTableHelper());
        $ownedAdoptTable->buildTable($stmt, $fields);
        $document->add($ownedAdoptTable);

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
        $genders = new LinkedHashMap();
        $genders->put(new MysString("female"), new MysString("f"));
        $genders->put(new MysString("male"), new MysString("m"));

        $ownedAdoptForm = new FormBuilder("addform", "add", "post");
        $ownedAdoptForm->buildComment("<u><strong>Create A New Adoptable For a User:</strong></u>")
                       ->buildComment("Adoptable Type: ", false)->buildTextField("type")
                       ->buildComment("Adoptable Name: ", false)->buildTextField("name")
                       ->buildComment("Adoptable Owner: ", false)->buildTextField("owner")
                       ->buildComment("Adoptable Clicks: ", false)->buildTextField("clicks")
                       ->buildComment("Adoptable Level: ", false)->buildTextField("level")
                       ->buildComment("Adoptable Alternate ID: ", false)->buildTextField("alternate")
                       ->buildComment("Adoptable Gender: ", false)
                       ->buildRadioList("gender", $genders)
                       ->buildButton("Give it to User", "submit", "submit");
        $document->add($ownedAdoptForm);
    }

    public function edit()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;
        if (!$mysidia->input->get("aid")) {
            $this->index();
            return;
        }

        $ownedadopt = $this->getField("ownedadopt")->get();
        $document->setTitle($this->lang->edit_title);
        $document->addLangvar($this->lang->edit);
        $genders = new LinkedHashMap();
        $genders->put(new MysString("female"), new MysString("f"));
        $genders->put(new MysString("male"), new MysString("m"));

        $ownedAdoptForm = new FormBuilder("editform", $mysidia->input->get("aid"), "post");
        $ownedAdoptForm->buildComment("<u><strong>Edit User's Owned Adoptable:</strong></u>")
                       ->buildComment("Adoptable Type: ", false)->buildTextField("type", $ownedadopt->type)
                       ->buildComment("Adoptable Name: ", false)->buildTextField("name", $ownedadopt->name)
                       ->buildComment("Adoptable Owner: ", false)->buildTextField("owner", $ownedadopt->owner)
                       ->buildComment("Adoptable Clicks: ", false)->buildTextField("clicks", $ownedadopt->totalclicks)
                       ->buildComment("Adoptable Level: ", false)->buildTextField("level", $ownedadopt->currentlevel)
                       ->buildComment("Adoptable Alternate ID: ", false)->buildTextField("alternate", $ownedadopt->alternate)
                       ->buildComment("Adoptable Gender: ", false)
                       ->buildRadioList("gender", $genders, $ownedadopt->gender)
                       ->buildButton("Edit this Adoptable", "submit", "submit");
        $document->add($ownedAdoptForm);
    }

    public function delete()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;
        if (!$mysidia->input->get("aid")) {
            $this->index();
            return;
        }
        $document->setTitle($this->lang->delete_title);
        $document->addLangvar($this->lang->delete);
        header("Refresh:3; URL='../index'");
    }

    private function dataValidate()
    {
        $mysidia = Registry::get("mysidia");
        $fields = ["type" => $mysidia->input->post("type"), "name" => $mysidia->input->post("name"), "owner" => $mysidia->input->post("owner"), "clicks" => $mysidia->input->post("clicks"),
                        "level" => $mysidia->input->post("level"), "usealternates" => $mysidia->input->post("usealternates"), "gender" => $mysidia->input->post("gender")];
        foreach ($fields as $field => $value) {
            if (!$value) {
                if ($field == "clicks" and $value == 0) {
                    continue;
                }
                if ($field == "usealternates") {
                    continue;
                }
                if ($field == "level" and $value == 0) {
                    continue;
                }
                throw new BlankFieldException("You did not enter in {$field} for the adoptable.  Please go back and try again.");
            }
        }
        return true;
    }
}
