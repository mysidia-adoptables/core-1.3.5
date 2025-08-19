<?php

use Resource\Native\String;
use Resource\Collection\LinkedList;
use Resource\Collection\LinkedHashMap;

class ACPAlternateView extends View
{
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
        $alternateForm = new FormBuilder("addform", "add", "post");
        $title = new Comment("Create a New Alt-Form:");
        $title->setBold();
        $title->setUnderlined();
        $alternateForm->add($title);

        $alternateForm->add(new Comment("<br><b>Adoptable Type</b>: ", false));
        $alternateForm->add(new Comment($this->lang->adopt_explain));
        $alternateForm->buildDropdownList("adopt", "AdoptTypeList");
        $alternateForm->add(new Comment("<br><br><b>Alt-Form Image</b>: ", false));
        $alternateForm->add(new Comment($this->lang->image_explain));
        $alternateForm->add(new TextField("imageurl"));
        $alternateForm->add(new Comment("<b>Or select an existing image</b>: "));
        $alternateForm->buildDropdownList("existingimageurl", "ImageList");

        $alternateForm->add(new Comment("<br><br><b>Starting Level</b>: ", false));
        $alternateForm->add(new Comment($this->lang->level_explain));
        $alternateForm->add(new TextField("level"));
        $alternateForm->add(new Comment("<br><br><b>Item</b>: ", false));
        $alternateForm->add(new Comment($this->lang->item_explain));
        $alternateForm->buildDropdownList("item", "ItemNameList");

        $genderList = new RadioList("gender");
        $genderList->add(new RadioButton("Male", "gender", "male"));
        $genderList->add(new RadioButton("Female", "gender", "female"));
        $genderList->add(new RadioButton("Both", "gender", "both"));
        $genderList->check("both");
        $alternateForm->add(new Comment("<br><br><b>Gender</b>: ", false));
        $alternateForm->add(new Comment($this->lang->gender_explain));
        $alternateForm->add($genderList);

        $alternateForm->add(new Comment("<br><br><b>Last Alt Form</b>: "));
        $alternateForm->add(new Comment($this->lang->lastalt_explain));
        $alternateForm->add(new TextField("lastalt"));
        $alternateForm->add(new Comment("<br><br><b>Chance for alt-form to be chosen</b>: "));
        $alternateForm->add(new Comment($this->lang->chance_explain));
        $alternateForm->add(new TextField("chance"));
        $alternateForm->add(new Button("Create Alt-Form", "submit", "submit"));
        $document->add($alternateForm);
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
        } elseif (!$mysidia->input->post("adopt")) {
            if (!is_numeric((string)$mysidia->input->get("alid"))) {
                $document->setTitle($this->lang->manage_alternate);
                $document->addLangvar($this->lang->manage_explain);
                $alternateTable = new TableBuilder("alternate");
                $alternateTable->setAlign(new Align("center", "middle"));
                $alternateTable->buildHeaders("AltID", "Image", "Level", "Gender", "Chance", "Edit", "Delete");

                $stmt = $this->getField("stmt")->get();
                $stmt2 = $this->getField("stmt2")->get();
                $alternates = $stmt->fetchAll(PDO::FETCH_GROUP);
                $levels = $stmt2->fetchAll(PDO::FETCH_GROUP);

                for ($i = 1; $i <= count($levels); $i++) {
                    $primaryCells = new LinkedList();
                    $primaryCells->add(new TCell("N/A"));
                    $primaryCells->add(new TCell(new Image($levels[$i][0]['primaryimage'])));
                    $primaryCells->add(new TCell($i));
                    $primaryCells->add(new TCell("Both"));
                    $primaryCells->add(new TCell("Default"));
                    $primaryCells->add(new TCell(new Image("templates/icons/no.gif")));
                    $primaryCells->add(new TCell(new Image("templates/icons/no.gif")));
                    $alternateTable->buildRow($primaryCells);

                    if ($alternates[$i]) {
                        foreach ($alternates[$i] as $alternate) {
                            $alternateCells = new LinkedList();
                            $alternateCells->add(new TCell($alternate['alid']));
                            $alternateCells->add(new TCell(new Image($alternate['image'])));
                            $alternateCells->add(new TCell($i));
                            $alternateCells->add(new TCell($alternate['gender']));
                            $alternateCells->add(new TCell("{$alternate['chance']}%"));
                            $alternateCells->add(new TCell(new Link("admincp/alternate/edit/{$mysidia->input->post("type")}/{$alternate['alid']}", new Image("templates/icons/cog.gif"))));
                            $alternateCells->add(new TCell(new Link("admincp/alternate/delete/{$mysidia->input->post("type")}/{$alternate['alid']}", new Image("templates/icons/delete.gif"))));
                            $alternateTable->buildRow($alternateCells);
                        }
                    }
                }
                $document->add($alternateTable);
            } else {
                $alternate = $this->getField("alternate")->get();
                $document->setTitle($this->lang->edit_title);
                $document->addLangvar($this->lang->edit);
                $alternateForm = new FormBuilder("editform", $mysidia->input->get("alid"), "post");
                $title = new Comment("Edit an adoptable Alt-Form:");
                $title->setBold();
                $title->setUnderlined();
                $alternateForm->add($title);

                $alternateForm->add(new Comment("<br><b>Adoptable Type</b>: ", false));
                $alternateForm->add(new Comment($this->lang->adopt_explain));
                $alternateForm->buildDropdownList("adopt", "AdoptTypeList", $alternate->adopt);
                $alternateForm->add(new Comment("<br><br><b>Alt-Form Image</b>: ", false));
                $alternateForm->add(new Comment($this->lang->image_explain));
                $alternateForm->add(new TextField("imageurl", $alternate->image));
                $alternateForm->add(new Comment("<b>Or select an existing image</b>: "));
                $alternateForm->buildDropdownList("existingimageurl", "ImageList", $alternate->image);

                $alternateForm->add(new Comment("<br><br><b>Starting Level</b>: ", false));
                $alternateForm->add(new Comment($this->lang->level_explain));
                $alternateForm->add(new TextField("level", $alternate->level));
                $alternateForm->add(new Comment("<br><br><b>Item</b>: ", false));
                $alternateForm->add(new Comment($this->lang->item_explain));
                $alternateForm->buildDropdownList("item", "ItemNameList", $alternate->item);

                $genderList = new RadioList("gender");
                $genderList->add(new RadioButton("Male", "gender", "male"));
                $genderList->add(new RadioButton("Female", "gender", "female"));
                $genderList->add(new RadioButton("Both", "gender", "both"));
                $genderList->check($alternate->gender);
                $alternateForm->add(new Comment("<br><br><b>Gender</b>: ", false));
                $alternateForm->add(new Comment($this->lang->gender_explain));
                $alternateForm->add($genderList);

                $alternateForm->add(new Comment("<br><br><b>Last Alt Form</b>: "));
                $alternateForm->add(new Comment($this->lang->lastalt_explain));
                $alternateForm->add(new TextField("lastalt", $alternate->lastalt));
                $alternateForm->add(new Comment("<br><br><b>Chance for alt-form to be chosen</b>: "));
                $alternateForm->add(new Comment($this->lang->chance_explain));
                $alternateForm->add(new TextField("chance", $alternate->chance));
                $alternateForm->add(new PasswordField("hidden", "adopt", $mysidia->input->get("type")));
                $alternateForm->add(new Button("Edit Alt-Form", "submit", "submit"));
                $document->add($alternateForm);
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

        if (!$mysidia->input->get("type") || !$mysidia->input->get("alid")) {
            $this->edit();
        } else {
            $document->setTitle($this->lang->delete_title);
            $document->addLangvar($this->lang->delete);
        }
    }
}
