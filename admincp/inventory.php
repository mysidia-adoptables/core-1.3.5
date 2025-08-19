<?php

class ACPInventoryController extends AppController
{
    public const PARAM = "iid";

    public function __construct()
    {
        parent::__construct();
        $mysidia = Registry::get("mysidia");
        if ($mysidia->usergroup->getpermission("canmanagesettings") != "yes") {
            throw new NoPermissionException("You do not have permission to manage item inventory.");
        }
    }

    public function index()
    {
        parent::index();
        $mysidia = Registry::get("mysidia");
        $total = $mysidia->db->select("inventory")->rowCount();
        if ($total == 0) {
            throw new InvalidIDException("default_none");
        }
        $pagination = new Pagination($total, $mysidia->settings->pagination, "admincp/inventory");
        $pagination->setPage($mysidia->input->get("page"));
        $stmt = $mysidia->db->select("inventory", [], "1 LIMIT {$pagination->getLimit()},{$pagination->getRowsperPage()}");
        $this->setField("pagination", $pagination);
        $this->setField("stmt", new DatabaseStatement($stmt));
    }

    public function add()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->post("submit")) {
            $this->dataValidate();
            $inventory = $mysidia->db->select("inventory", [], "itemname = '{$mysidia->input->post("itemname")}' and owner = '{$mysidia->input->post("owner")}'")->fetchObject();
            if (is_object($inventory)) {
                $newquantity = $inventory->quantity + $mysidia->input->post("quantity");
                $mysidia->db->update("inventory", ["quantity" => $newquantity], "itemname='{$mysidia->input->post("itemname")}' and owner='{$mysidia->input->post("owner")}'");
            } else {
                $item = $mysidia->db->select("items", ["id","category"], "itemname = '{$mysidia->input->post("itemname")}'")->fetchObject();
                $mysidia->db->insert("inventory", ["iid" => null, "category" => $item->category, "itemname" => $mysidia->input->post("itemname"),
                                                        "owner" => $mysidia->input->post("owner"), "quantity" => $mysidia->input->post("quantity"), "status" => 'Available']);
            }
        }
    }

    public function edit()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("iid")) {
            // An item has yet been selected, return to the index page.
            $this->index();
            return;
        } elseif ($mysidia->input->post("submit")) {
            $this->dataValidate();
            $mysidia->db->update("inventory", ["itemname" => $mysidia->input->post("itemname"), "owner" => $mysidia->input->post("owner"), "quantity" => $mysidia->input->post("quantity")], "iid='{$mysidia->input->get("iid")}'");
            return;
        } else {
            $inventory = $mysidia->db->select("inventory", [], "iid='{$mysidia->input->get("iid")}'")->fetchObject();
            if (!is_object($inventory)) {
                throw new InvalidIDException("nonexist");
            }
            $this->setField("inventory", new DataMysObject($inventory));
        }
    }

    public function delete()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("iid")) {
            // An item has yet been selected, return to the index page.
            $this->index();
            return;
        }
        $mysidia->db->delete("inventory", "iid='{$mysidia->input->get("iid")}'");
    }

    private function dataValidate()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->post("itemname")) {
            throw new BlankFieldException("itemname");
        }
        if (!$mysidia->input->post("owner")) {
            throw new BlankFieldException("owner");
        }
        if (!is_numeric($mysidia->input->post("quantity")) or $mysidia->input->post("quantity") < 0) {
            throw new BlankFieldException("quantity");
        }
        header("Refresh:3; URL='../../index'");
        return true;
    }
}
