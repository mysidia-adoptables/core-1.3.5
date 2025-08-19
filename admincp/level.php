<?php

use Resource\Native\Integer;
use Resource\Native\MysString;
use Resource\Collection\LinkedHashMap;

class ACPLevelController extends AppController
{
    public const PARAM = "type";
    public const PARAM2 = "level";
    private $settings;

    public function __construct()
    {
        parent::__construct();
        $this->settings = new LevelSetting();
        $mysidia = Registry::get("mysidia");
        if ($mysidia->usergroup->getpermission("canmanageadopts") != "yes") {
            throw new NoPermissionException("You do not have permission to manage levels.");
        }
    }

    public function index(): never
    {
        $mysidia = Registry::get("mysidia");
        throw new InvalidActionException("global_action");
    }

    public function add()
    {
        $mysidia = Registry::get("mysidia");

        if (!(string)$mysidia->input->get("type") and !$mysidia->input->post("type")) {
            return;
        } elseif ($mysidia->input->post("adoptiename")) {
            if ($mysidia->session->fetch("acpLevel") != "add") {
                $this->setFlag("global_error", "Session already expired...");
                return;
            }

            $type = $mysidia->input->post("adoptiename");
            $currentLevel = $mysidia->input->post("currentlevel");
            $primaryHosted = $mysidia->input->post("primaryhosted");
            $primaryLocal = $mysidia->input->post("primarylocal");
            $reqclicks = $mysidia->input->post("reqclicks");
            $prevclicks = $mysidia->input->post("prevclicks");

            for ($i = $currentLevel; $i < $this->settings->maximum + 1; $i++) {
                try {
                    $n = $i - $currentLevel;
                    $this->dataValidate($n);
                    $primimg = ($primaryHosted[$n] and $primaryLocal[$n] == "none") ? $primaryHosted[$n] : $primaryLocal[$n];
                    $altimg = ($altLocal[$n] == "none") ? $altHosted[$n] : $altLocal[$n];
                    $mysidia->db->insert("levels", ["lvid" => null, "adoptiename" => $type, "thisislevel" => $i, "requiredclicks" => $reqclicks[$n],
                                                                          "primaryimage" => $primimg, "rewarduser" => $mysidia->input->post("isreward"), "promocode" => $mysidia->input->post("rewardcode")]);
                } catch (Exception $e) {
                    if ($i == $currentLevel) {
                        throw $e;
                    } else {
                        break;
                    }
                }
            }
        } else {
            $type = $mysidia->input->get("type") ?: $mysidia->input->post("type");
            $stmt = $mysidia->db->select("adoptables", ["type", "class", "alternates"], "type='{$type}'");
            if ($adopt = $stmt->fetchObject()) {
                $mysidia->session->assign("acpLevel", "add", true);
                $alt = strtoupper((string) $adopt->alternates);
                if ($alt != "ENABLED") {
                    $alt = "DISABLED";
                }

                $currentlevel = $mysidia->db->select("levels", [], "adoptiename='{$type}'")->rowCount();
                if ($currentlevel > $this->settings->maximum) {
                    throw new InvalidActionException("maximum");
                }
                $previouslevel = $currentlevel - 1;
                $prevlevelclicks = $mysidia->db->select("levels", ["requiredclicks"], "adoptiename = '{$type}' and thisislevel = '{$previouslevel}'")->fetchColumn();
                $num = $this->settings->maximum - $previouslevel;
                $description = "This page will allow you to create new level(s) for {$type}.  Right now there are 
								<b>{$previouslevel}</b> levels that exist for {$type}, the level cap is {$this->settings->maximum}. 
								That means that you can create up to <b>{$num}</b> levels for {$type}.
								right now.<br><br><br>";

                $this->setField("settings", $this->settings);
                $this->setField("type", new MysString($type));
                $this->setField("alt", new MysString($alt));
                $this->setField("currentlevel", new Integer($currentlevel));
                $this->setField("previouslevel", new Integer($previouslevel));
                $this->setField("prevlevelclicks", new Integer($prevlevelclicks));
                $this->setField("description", new MysString($description));
            } else {
                throw new InvalidIDException("global_id");
            }
        }
    }

    public function edit()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("type") and !$mysidia->input->post("type")) {
            return;
        } elseif (!$mysidia->input->post("adoptiename")) {
            $type = $mysidia->input->get("type") ?: $mysidia->input->post("type");
            $stmt = $mysidia->db->select("adoptables", [], "type='{$type}'");
            if ($adopt = $stmt->fetchObject()) {
                if (!is_numeric((string)$mysidia->input->get("level"))) {
                    $stmt = $mysidia->db->select("levels", [], "adoptiename='{$mysidia->input->post("type")}' and thisislevel != '0' ORDER BY thisislevel ASC");
                    $this->setField("stmt", new DatabaseStatement($stmt));
                    $this->setField("eggimage", new Image($adopt->eggimage));
                } else {
                    $level = $mysidia->db->select("levels", [], "adoptiename='{$mysidia->input->get("type")}' and thisislevel='{$mysidia->input->get("level")}'")->fetchObject();
                    if (!is_object($level)) {
                        throw new InvalidIDException("nonexist");
                    }
                    $this->setField("level", new DataMysObject($level));
                }
            } else {
                throw new InvalidIDException("global_id");
            }
        } elseif ($mysidia->input->post("adoptiename") and $mysidia->input->post("submit")) {
            $stmt = $mysidia->db->select("levels", [], "adoptiename='{$mysidia->input->get("type")}' AND thisislevel='{$mysidia->input->get("level")}'");
            if ($level = $stmt->fetchObject()) {
                $primaryHosted = $mysidia->input->post("primaryhosted");
                $primaryLocal = $mysidia->input->post("primarylocal");
                $reqclicks = $mysidia->input->post("reqclicks");
                $primimg = ($primaryHosted and $primaryLocal == "none") ? $primaryHosted : $primaryLocal;

                if ($primimg and $primimg != "none") {
                    $mysidia->db->update("levels", ["primaryimage" => $primimg], "adoptiename='{$mysidia->input->get("type")}' AND thisislevel='{$mysidia->input->get("level")}'");
                }
                if ($reqclicks) {
                    $mysidia->db->update("levels", ["requiredclicks" => $reqclicks], "adoptiename='{$mysidia->input->get("type")}' AND thisislevel='{$mysidia->input->get("level")}'");
                }
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
        if (!$mysidia->input->get("type") and !$mysidia->input->post("type")) {
            return;
        } elseif (!$mysidia->input->post("adoptiename")) {
            $this->edit();
        } elseif ($mysidia->input->post("adoptiename") and $mysidia->input->post("submit")) {
            $stmt = $mysidia->db->select("levels", [], "adoptiename='{$mysidia->input->get("type")}' AND thisislevel='{$mysidia->input->get("level")}'");
            if ($level = $stmt->fetchObject()) {
                $mysidia->db->delete("levels", "adoptiename='{$mysidia->input->get("type")}' AND thisislevel>='{$mysidia->input->get("level")}'");
            } else {
                throw new InvalidIDException("global_id");
            }
        } else {
            throw new InvalidIDException("global_id");
        }
    }

    public function settings()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->post("submit")) {
            $settings = ['system', 'method', 'maximum', 'clicks',
                              'number', 'reward', 'owner'];
            foreach ($settings as $name) {
                if ($mysidia->input->post($name) != ($this->settings->{$name})) {
                    $mysidia->db->update("levels_settings", ["value" => $mysidia->input->post($name)], "name='{$name}'");
                }
            }
            return;
        }
        $this->setField("levelSettings", $this->settings);
    }

    public function daycare()
    {
        $mysidia = Registry::get("mysidia");
        $daycareSettings = new DaycareSetting($mysidia->db);
        if ($mysidia->input->post("submit")) {
            $settings = ['system', 'display', 'number', 'columns', 'level', 'species', 'info', 'owned'];
            foreach ($settings as $name) {
                if ($mysidia->input->post($name) != ($daycareSettings->$name)) {
                    $mysidia->db->update("daycare_settings", ["value" => $mysidia->input->post($name)], "name='{$name}'");
                }
            }
            return;
        }
        $this->setField("daycareSettings", $daycareSettings);
    }

    private function dataValidate($i)
    {
        $mysidia = Registry::get("mysidia");
        $type = $mysidia->input->post("adoptiename");
        $currentLevel = $mysidia->input->post("currentlevel");
        $primaryHosted = $mysidia->input->post("primaryhosted");
        $primaryLocal = $mysidia->input->post("primarylocal");
        $reqclicks = $mysidia->input->post("reqclicks");
        $prevclicks = $mysidia->input->post("prevclicks");

        if (!$type or !$currentLevel) {
            throw new BlankFieldException("name");
        }
        if (!$primaryHosted[$i] and !$primaryLocal[$i]) {
            throw new BlankFieldException("primary_image");
        }
        if (!$primaryHosted[$i] and $primaryLocal[$i] == "none") {
            throw new BlankFieldException("primary_image");
        }
        if (!is_numeric($reqclicks[$i])) {
            throw new BlankFieldException("clicks");
        }
        if ($prevclicks >= $reqclicks[$i]) {
            throw new InvalidActionException("clicks2");
        }
        return true;
    }
}
