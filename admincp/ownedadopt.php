<?php

class ACPOwnedadoptController extends AppController
{
    public const PARAM = "aid";

    public function __construct()
    {
        parent::__construct();
        $mysidia = Registry::get("mysidia");
        if ($mysidia->usergroup->getpermission("canmanageadopts") != "yes") {
            throw new NoPermissionException("You do not have permission to manage adoptables.");
        }
    }

    public function index()
    {
        parent::index();
        $mysidia = Registry::get("mysidia");
        $total = $mysidia->db->select("owned_adoptables")->rowCount();
        if ($total == 0) {
            throw new InvalidIDException("empty");
        }
        $pagination = new Pagination($total, $mysidia->settings->pagination, "admincp/ownedadopt");
        $pagination->setPage($mysidia->input->get("page"));
        $stmt = $mysidia->db->select("owned_adoptables", [], "1 LIMIT {$pagination->getLimit()},{$pagination->getRowsperPage()}");
        $this->setField("pagination", $pagination);
        $this->setField("stmt", new DatabaseStatement($stmt));
    }

    public function add()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->post("submit")) {
            $this->dataValidate();
            $mysidia->db->insert("owned_adoptables", ["aid" => null, "type" => $mysidia->input->post("type"), "name" => $mysidia->input->post("name"), "owner" => $mysidia->input->post("owner"), "currentlevel" => $mysidia->input->post("level"), "totalclicks" => $mysidia->input->post("clicks"),
                                                           "code" => codegen(10, 0), "imageurl" => null, "alternate" => $mysidia->input->post("alternate"), "tradestatus" => 'fortrade', "isfrozen" => 'no', "gender" => $mysidia->input->post("gender"), "offsprings" => 0, "lastbred" => 0]);
        }
    }

    public function edit()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("aid")) {
            $this->index();
            return;
        }
        if ($mysidia->input->post("submit")) {
            $this->dataValidate();
            $mysidia->db->update("owned_adoptables", ["type" => $mysidia->input->post("type"), "name" => $mysidia->input->post("name"), "owner" => $mysidia->input->post("owner"), "totalclicks" => $mysidia->input->post("clicks"),
                                                           "currentlevel" => $mysidia->input->post("level"), "alternate" => $mysidia->input->post("alternate"), "gender" => $mysidia->input->post("gender")], "aid='{$mysidia->input->get("aid")}'");
            return;
        }

        $stmt = $mysidia->db->select("owned_adoptables", [], "aid='{$mysidia->input->get("aid")}'");
        if ($ownedadopt = $stmt->fetchObject()) {
            $this->setField("ownedadopt", new DataMysObject($ownedadopt));
        } else {
            throw new InvalidIDException("global_id");
        }
    }

    public function delete()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("aid")) {
            $this->index();
            return;
        }
        $mysidia->db->delete("owned_adoptables", "aid='{$mysidia->input->get("aid")}'");
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
