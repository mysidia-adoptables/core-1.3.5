<?php

class ACPPromoController extends AppController
{
    public const PARAM = "pid";

    public function __construct()
    {
        parent::__construct();
        $mysidia = Registry::get("mysidia");
        if ($mysidia->usergroup->getpermission("canmanagesettings") != "yes") {
            throw new NoPermissionException("You do not have permission to manage promocode.");
        }
    }

    public function index()
    {
        parent::index();
        $mysidia = Registry::get("mysidia");
        $total = $mysidia->db->select("promocodes")->rowCount();
        if ($total == 0) {
            throw new InvalidIDException("default_none");
        }
        $pagination = new Pagination($total, $mysidia->settings->pagination, "admincp/promo");
        $pagination->setPage($mysidia->input->get("page"));
        $stmt = $mysidia->db->select("promocodes", [], "1 LIMIT {$pagination->getLimit()},{$pagination->getRowsperPage()}");
        $this->setField("pagination", $pagination);
        $this->setField("stmt", new DatabaseStatement($stmt));
    }

    public function add()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->post("submit")) {
            $this->dataValidate();
            $oldcode = $mysidia->db->select("promocodes", ["code"], "code='{$mysidia->input->post("promocode")}'")->fetchColumn();
            if ($mysidia->input->post("promocode") == $oldcode) {
                throw new DuplicateIDException("code_duplicate");
            }
            $mysidia->db->insert("promocodes", ["pid" => null, "type" => $mysidia->input->post("type"), "user" => $mysidia->input->post("user"), "code" => $mysidia->input->post("promocode"), "availability" => $mysidia->input->post("availability"),
                                                     "fromdate" => $mysidia->input->post("fromdate"), "todate" => $mysidia->input->post("todate"), "reward" => $mysidia->input->post("reward")]);
        }
    }

    public function edit()
    {
        $mysidia = Registry::get("mysidia");
        $document = $mysidia->frame->getDocument();
        if (!$mysidia->input->get("pid")) {
            // A promocode has yet been selected, return to the index page.
            $this->index();
            return;
        } elseif ($mysidia->input->post("submit")) {
            $this->dataValidate();
            $mysidia->db->update("promocodes", ["type" => $mysidia->input->post("type"), "user" => $mysidia->input->post("user"), "code" => $mysidia->input->post("promocode"), "availability" => $mysidia->input->post("availability"),
                                                     "fromdate" => $mysidia->input->post("fromdate"), "todate" => $mysidia->input->post("todate"), "reward" => $mysidia->input->post("reward")], "pid='{$mysidia->input->get("pid")}'");
            return;
        } else {
            $promo = $mysidia->db->select("promocodes", [], "pid='{$mysidia->input->get("pid")}'")->fetchObject();
            if (!is_object($promo)) {
                throw new InvalidIDException("nonexist");
            }
            $this->setField("promo", new DataMysObject($promo));
        }
    }

    public function delete()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("pid")) {
            // A promocode has yet been selected, return to the index page.
            $this->index();
            return;
        }
        $mysidia->db->delete("promocodes", "pid='{$mysidia->input->get("pid")}'");
    }

    private function dataValidate()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->post("type")) {
            throw new BlankFieldException("type");
        }
        if (!$mysidia->input->post("promocode")) {
            throw new BlankFieldException("code_none");
        }
        if (strtotime((string) $mysidia->input->post("fromdate")) > strtotime((string) $mysidia->input->post("todate"))) {
            throw new InvalidActionxception("date");
        }
        if (!is_numeric($mysidia->input->post("availability"))) {
            throw new BlankFieldException("availability");
        }
        if (!$mysidia->input->post("reward")) {
            throw new BlankFieldException("reward");
        }
        return true;
    }
}
