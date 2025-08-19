<?php

use Resource\Native\MysString;
use Resource\Collection\LinkedHashMap;

class ACPModuleController extends AppController
{
    public const PARAM = "mid";

    public function __construct()
    {
        parent::__construct();
        $mysidia = Registry::get("mysidia");
        if ($mysidia->usergroup->getpermission("canmanagesettings") != "yes") {
            throw new NoPermissionException("You do not have permission to manage modules.");
        }
    }

    public function index()
    {
        parent::index();
        $mysidia = Registry::get("mysidia");
        $total = $mysidia->db->select("modules")->rowCount();
        $pagination = new Pagination($total, $mysidia->settings->pagination, "admincp/module");
        $pagination->setPage($mysidia->input->get("page"));
        $stmt = $mysidia->db->select("modules", [], "1 LIMIT {$pagination->getLimit()},{$pagination->getRowsperPage()}");
        $this->setField("pagination", $pagination);
        $this->setField("stmt", new DatabaseStatement($stmt));
    }

    public function add()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->post("submit")) {
            if (!$mysidia->input->post("widget") or !$mysidia->input->post("name")) {
                throw new BlankFieldException($mysidia->lang->global_blank);
            }
            $userLevel = (!$mysidia->input->post("userlevel")) ? "user" : $mysidia->input->post("userlevel");
            $html = $this->format($mysidia->input->post("html"));
            $php = $this->format($mysidia->input->post("php"));
            $mysidia->db->insert("modules", ["moid" => null, "widget" => $mysidia->input->post("widget"), "name" => $mysidia->input->post("name"), "subtitle" => $mysidia->input->post("subtitle"), "userlevel" => $userLevel,
                                                  "html" => $html, "php" => $php, "order" => $mysidia->input->post("order"), "status" => $mysidia->input->post("status")]);
            return;
        }
        $stmt = $mysidia->db->select("widgets", ["name"], "wid > 3");
        $widgets = $mysidia->db->fetchList($stmt);
        $this->setField("widgets", $widgets);
    }

    public function edit()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("mid")) {
            // A module has yet been selected, return to the index page.
            $this->index();
            return;
        } elseif ($mysidia->input->post("submit")) {
            $userLevel = (!$mysidia->input->post("userlevel")) ? "user" : $mysidia->input->post("userlevel");
            $html = $this->format($mysidia->input->post("html"));
            $php = $this->format($mysidia->input->post("php"));
            $mysidia->db->update("modules", ["widget" => $mysidia->input->post("widget"), "name" => $mysidia->input->post("name"), "subtitle" => $mysidia->input->post("subtitle"), "userlevel" => $userLevel,
                                                  "html" => $html, "php" => $php, "order" => $mysidia->input->post("order"), "status" => $mysidia->input->post("status")], "moid='{$mysidia->input->get("mid")}'");
            return;
        } else {
            $module = $mysidia->db->select("modules", [], "moid='{$mysidia->input->get("mid")}'")->fetchObject();
            if (!is_object($module)) {
                throw new InvalidIDException("global_id");
            }
            $stmt = $mysidia->db->select("widgets", ["name"], "wid > 3");
            $widgets = $mysidia->db->fetchList($stmt);
            $this->setField("module", new DataMysObject($module));
            $this->setField("widgets", $widgets);
        }
    }

    public function delete()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("mid")) {
            // A module has yet been selected, return to the index page.
            $this->index();
            return;
        }
        $mysidia->db->delete("modules", "moid='{$mysidia->input->get("mid")}'");
    }

    private function format($text)
    {
        $text = html_entity_decode((string) $text);
        $text = stripslashes($text);
        return $text;
    }
}
