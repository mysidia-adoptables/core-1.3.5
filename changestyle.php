<?php

use Resource\Native\MysString;
use Resource\Collection\LinkedHashMap;

class ChangeStyleController extends AppController
{
    public const PARAM = "theme";

    public function __construct()
    {
        parent::__construct("member");
    }

    public function index()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->get("theme")) {
            $stmt = $mysidia->db->select("themes", [], "themefolder = '{$mysidia->input->get("theme")}'");
            if ($theme = $stmt->fetchObject()) {
                $mysidia->db->update("users_options", ["theme" => $mysidia->input->get("theme")], "username = '{$mysidia->user->username}'");
                $mysidia->user->theme = $mysidia->input->get("theme");
            } else {
                throw new InvalidIDException("fail");
            }
            return;
        }

        $themes = new LinkedHashMap();
        $stmt = $mysidia->db->select("themes");
        while ($theme = $stmt->fetchObject()) {
            $themes->put(new MysString($theme->themename), new MysString($theme->themefolder));
        }
        $this->setField("themes", $themes);
    }
}
