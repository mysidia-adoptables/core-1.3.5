<?php

use Resource\Native\MysString;
use Resource\Collection\LinkedHashMap;

class ACPWidgetController extends AppController
{
    public const PARAM = "wid";

    public function __construct()
    {
        parent::__construct();
        $mysidia = Registry::get("mysidia");
        if ($mysidia->usergroup->getpermission("canmanagesettings") != "yes") {
            throw new NoPermissionException("You do not have permission to manage widgets.");
        }
    }

    public function index()
    {
        parent::index();
        $mysidia = Registry::get("mysidia");
        $total = $mysidia->db->select("widgets")->rowCount();
        $pagination = new Pagination($total, $mysidia->settings->pagination, "admincp/widget");
        $pagination->setPage($mysidia->input->get("page"));
        $stmt = $mysidia->db->select("widgets", [], "1 LIMIT {$pagination->getLimit()},{$pagination->getRowsperPage()}");
        $this->setField("pagination", $pagination);
        $this->setField("stmt", new DatabaseStatement($stmt));
    }

    public function add()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->post("submit")) {
            if (!$mysidia->input->post("name")) {
                throw new BlankFieldException("global_blank");
            }
            $userLevel = (!$mysidia->input->post("controllers")) ? "main" : $mysidia->input->post("controllers");
            $mysidia->db->insert("widgets", ["wid" => null, "name" => $mysidia->input->post("name"), "controller" => $mysidia->input->post("controllers"),
                                                  "order" => $mysidia->input->post("order"), "status" => $mysidia->input->post("status")]);
        }
    }

    public function edit()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("wid")) {
            // A widget has yet been selected, return to the index page.
            $this->index();
            return;
        } elseif ($mysidia->input->post("submit")) {
            if (!$mysidia->input->post("name")) {
                throw new BlankFieldException("global_blank");
            }
            $mysidia->db->update("widgets", ["name" => $mysidia->input->post("name"), "controller" => $mysidia->input->post("controllers"),
                                                  "order" => $mysidia->input->post("order"), "status" => $mysidia->input->post("status")], "wid='{$mysidia->input->get("wid")}'");
            return;
        } else {
            $widget = $mysidia->db->select("widgets", [], "wid='{$mysidia->input->get("wid")}'")->fetchObject();
            if (!is_object($widget)) {
                throw new InvalidIDException("global_id");
            }
            $this->setField("widget", new DataMysObject($widget));
        }
    }

    public function delete()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("wid")) {
            // A widget has yet been selected, return to the index page.
            $this->index();
            return;
        }
        if ($mysidia->input->get("wid")->getValue() < 6) {
            throw new InvalidActionException("internal");
        }
        $mysidia->db->delete("widgets", "wid='{$mysidia->input->get("wid")}'");
    }
}
