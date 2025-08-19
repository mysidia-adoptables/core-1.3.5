<?php

use Resource\Native\MysString;

class ACPAlternateController extends AppController
{
    public const PARAM = "type";
    public const PARAM2 = "alid";

    public function __construct()
    {
        parent::__construct();
        $mysidia = Registry::get("mysidia");
        if ($mysidia->usergroup->getpermission("canmanageadopts") != "yes") {
            throw new NoPermissionException("You do not have permission to manage adoptable alternate-forms.");
        }
    }

    public function add()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->post("submit")) {
            $this->dataValidate();
            $imageurl = ($mysidia->input->post("existingimageurl") == "none") ? $mysidia->input->post("imageurl") : $mysidia->input->post("existingimageurl");
            $mysidia->db->insert("alternates", ["adopt" => $mysidia->input->post("adopt"), "image" => $imageurl, "level" => $mysidia->input->post("level"), "item" => $mysidia->input->post("item"),
                                                                        "gender" => $mysidia->input->post("gender"), "lastalt" => $mysidia->input->post("lastalt"), "chance" => $mysidia->input->post("chance")]);
        }
    }

    public function edit()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("type") and !$mysidia->input->post("type")) {
            return;
        } elseif (!$mysidia->input->post("adopt")) {
            $type = $mysidia->input->get("type") ?: $mysidia->input->post("type");
            $stmt = $mysidia->db->select("adoptables", [], "type='{$type}'");
            if ($adopt = $stmt->fetchObject()) {
                if (!is_numeric((string)$mysidia->input->get("alid"))) {
                    $stmt = $mysidia->db->select("alternates", ["level", "alid", "adopt", "image", "gender", "chance"], "adopt='{$mysidia->input->post("type")}' ORDER BY level ASC, chance DESC");
                    $this->setField("stmt", new DatabaseStatement($stmt));
                    $stmt2 = $mysidia->db->select("levels", ["thisislevel", "adoptiename", "primaryimage"], "adoptiename='{$mysidia->input->post("type")}' AND thisislevel != 0 ORDER BY thisislevel ASC");
                    $this->setField("stmt2", new DatabaseStatement($stmt2));
                } else {
                    $alternate = $mysidia->db->select("alternates", [], "alid='{$mysidia->input->get("alid")}'")->fetchObject();
                    if (!is_object($alternate)) {
                        throw new InvalidIDException("nonexist");
                    }
                    $this->setField("alternate", new DataMysObject($alternate));
                }
            } else {
                throw new InvalidIDException("global_id");
            }
        } elseif ($mysidia->input->post("adopt") and $mysidia->input->post("submit")) {
            $stmt = $mysidia->db->select("alternates", [], "alid='{$mysidia->input->get("alid")}'");
            if ($alternate = $stmt->fetchObject()) {
                $this->dataValidate();
                $imageurl = ($mysidia->input->post("existingimageurl") == "none") ? $mysidia->input->post("imageurl") : $mysidia->input->post("existingimageurl");
                $mysidia->db->update("alternates", ["adopt" => $mysidia->input->post("adopt"), "image" => $imageurl, "level" => $mysidia->input->post("level"), "item" => $mysidia->input->post("item"),
                                                                             "gender" => $mysidia->input->post("gender"), "lastalt" => $mysidia->input->post("lastalt"), "chance" => $mysidia->input->post("chance")], "alid='{$mysidia->input->get("alid")}'");
            } else {
                throw new InvalidIDException("global_id");
            }
        } else {
            throw new InvalidIDException("global_id");
        }
    }

    public function delete()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("type") || !$mysidia->input->get("alid")) {
            // An alternate form has yet been selected, return to the manage page.
            $this->edit();
        } else {
            $mysidia->db->delete("alternates", "alid='{$mysidia->input->get("alid")}'");
        }
    }

    private function dataValidate()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->post("adopt")) {
            throw new BlankFieldException("adopt");
        }
        if (!$mysidia->input->post("imageurl") and $mysidia->input->post("existingimageurl") == "none") {
            throw new BlankFieldException("images");
        }
        if (!$mysidia->input->post("chance")) {
            throw new BlankFieldException("chance");
        }
        return true;
    }
}
