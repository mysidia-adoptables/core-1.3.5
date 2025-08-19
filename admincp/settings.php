<?php

use Resource\Native\MysString;
use Resource\Collection\LinkedList;
use Resource\Collection\LinkedHashMap;

class ACPSettingsController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $mysidia = Registry::get("mysidia");
        if ($mysidia->usergroup->getpermission("canmanagesettings") != "yes") {
            throw new NoPermissionException("You do not have permission to manage promocodes.");
        }
    }

    public function globals()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->post("submit")) {
            $settings = ['theme', 'sitename', 'browsertitle', 'cost',  'slogan', 'admincontact', 'systemuser', 'systememail', 'startmoney', 'pagination'];
            foreach ($settings as $name) {
                if ($mysidia->input->post($name) != ($mysidia->settings->{$name})) {
                    $mysidia->db->update("settings", ["value" => $mysidia->input->post($name)], "name='{$name}'");
                }
            }
        }
    }

    public function system()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->post("submit")) {
            $systems = ['site', 'adopts', 'friends', 'items',  'messages', 'online', 'promo', 'register', 'shops', 'shoutbox', 'vmessages'];
            foreach ($systems as $name) {
                $mysidia->db->update("systems", ["value" => $mysidia->input->post($name)], "name='{$name}'");
            }
            return;
        }
        $enabled = new LinkedHashMap();
        $enabled->put(new MysString(" Yes"), new MysString("enabled"));
        $enabled->put(new MysString(" No"), new MysString("disabled"));
        $this->setField("enabled", $enabled);
    }

    public function theme()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->post("submit") == "install") {
            if ($mysidia->input->post("themename") and $mysidia->input->post("themefolder")) {
                $mysidia->db->insert("themes", ["id" => null, "themename" => $mysidia->input->post("themename"), "themefolder" => $mysidia->input->post("themefolder")]);
                return;
            } else {
                throw new InvalidActionException("themes_install_failed");
            }
        }
        if ($mysidia->input->post("submit") == "update" and $mysidia->input->post("theme") != "none") {
            $stmt = $mysidia->db->select("themes", [], "themefolder='{$mysidia->input->post("theme")}'");
            if ($theme = $stmt->fetchObject()) {
                $mysidia->db->update("settings", ["value" => $mysidia->input->post("theme")], "name='theme'");
                return;
            } else {
                throw new InvalidIDException("themes_update_failed");
            }
        }
    }

    public function pound()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->post("submit")) {
            $active = [];
            $simplerb = ["system", "adopt", "date", "owner", "rename"];
            $poundsettings = ['system', 'adopt', 'specieslimit', 'cost', 'levelbonus', 'number', 'date', 'duration', 'owner', 'recurrence', 'rename'];

            foreach ($poundsettings as $varname) {
                if ($mysidia->input->post($varname) != "") {
                    if (!$mysidia->input->post($varname) and !in_array($varname, $simplerb)) {
                        $active[$varname] = "no";
                    } elseif (in_array($varname, $simplerb)) {
                        $active[$varname] = $mysidia->input->post($varname);
                    } else {
                        $active[$varname] = "yes";
                    }
                    $mysidia->db->update("pound_settings", ["active" => $active[$varname], "value" => $mysidia->input->post($varname)], "varname='{$varname}'");
                }
            }

            if ($mysidia->input->post("costtype")) {
                $mysidia->db->update("pound_settings", ["advanced" => $mysidia->input->post("costtype")], "varname='cost'");
            }
            if ($mysidia->input->post("leveltype")) {
                $mysidia->db->update("pound_settings", ["advanced" => $mysidia->input->post("leveltype")], "varname='levelbonus'");
            }
            if ($mysidia->input->post("dateunit")) {
                $mysidia->db->update("pound_settings", ["advanced" => $mysidia->input->post("dateunit")], "varname='duration'");
            }
            return;
        }

        $poundsettings = $mysidia->db->select("pound_settings", [])->fetchAll(PDO::FETCH_OBJ);
        $enabled = new LinkedHashMap();
        $enabled->put(new MysString(" Yes"), new MysString("yes"));
        $enabled->put(new MysString(" No"), new MysString("no"));
        $cost = new LinkedHashMap();
        $cost->put(new MysString(" Increment"), new MysString("increment"));
        $cost->put(new MysString(" Percent"), new MysString("percent"));
        $level = new LinkedHashMap();
        $level->put(new MysString(" Increment"), new MysString("increment"));
        $level->put(new MysString(" Multiply"), new MysString("multiply"));
        $rename = new LinkedHashMap();
        $rename->put(new MysString(" Original Owner Only"), new MysString("yes"));
        $rename->put(new MysString(" Everyone"), new MysString("no"));
        $this->setField("poundsettings", new DataMysObject($poundsettings));
        $this->setField("enabled", $enabled);
        $this->setField("cost", $cost);
        $this->setField("level", $level);
        $this->setField("rename", $rename);
    }

    public function plugin()
    {
        $mysidia = Registry::get("mysidia");
        $stmt = $mysidia->db->select("acp_hooks");
        if ($stmt->rowCount() == 0) {
            throw new InvalidIDException($mysidia->lang->no_plugins);
        }
        $plugins = new LinkedList();
        while ($plugin = $stmt->fetchObject()) {
            $plugins->add(new DataMysObject($plugins));
        }
        $this->setField("plugins", $plugins);
    }
}
