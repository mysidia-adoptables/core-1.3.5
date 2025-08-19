<?php

use Resource\Native\MysString;
use Resource\Collection\LinkedHashMap;

class ACPUsergroupController extends AppController
{
    public const PARAM = "group";

    public function __construct()
    {
        parent::__construct();
        $mysidia = Registry::get("mysidia");
        if ($mysidia->usergroup->getpermission("canmanageusers") != "yes") {
            throw new NoPermissionException("You do not have permission to manage users.");
        }
    }

    public function index()
    {
        parent::index();
        $mysidia = Registry::get("mysidia");
        $total = $mysidia->db->select("groups")->rowCount();
        $pagination = new Pagination($total, $mysidia->settings->pagination, "admincp/usergroup");
        $pagination->setPage($mysidia->input->get("page"));
        $stmt = $mysidia->db->select("groups", [], "1 LIMIT {$pagination->getLimit()},{$pagination->getRowsperPage()}");
        $this->setField("pagination", $pagination);
        $this->setField("stmt", new DatabaseStatement($stmt));
    }

    public function add()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->post("submit") and $mysidia->input->post("group")) {
            $stmt = $mysidia->db->select("groups", [], "groupname='{$mysidia->input->post("group")}'");
            if ($usergroup = $stmt->fetchObject()) {
                throw new DuplicateIDException("duplicate");
            }

            $mysidia->db->insert("groups", ["gid" => null, "groupname" => $mysidia->input->post("group"), "canadopt" => 'yes', "canpm" => 'yes', "cancp" => 'no', "canmanageadopts" => 'no',
                                                 "canmanagecontent" => 'no', "canmanageads" => 'no', "canmanagesettings" => 'no', "canmanageusers" => 'no']);
        }
    }

    public function edit()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("group")) {
            $this->index();
            return;
        }
        $usergroup = $mysidia->db->select("groups", [], "gid='{$mysidia->input->get("group")}'")->fetchObject();
        if (!is_object($usergroup)) {
            throw new InvalidIDException("global_id");
        }
        $permissions = ["canadopt", "canpm", "cancp", "canmanageusers", "canmanageadopts", "canmanagecontent", "canmanagesettings", "canmanageads"];

        if ($mysidia->input->post("submit")) {
            foreach ($permissions as $perm) {
                ${$perm} = ($mysidia->input->post($perm) != "yes") ? "no" : $mysidia->input->post($perm);
            }

            $mysidia->db->update("groups", ["canadopt" => $canadopt, "canpm" => $canpm, "cancp" => $cancp, "canmanageadopts" => $canmanageadopts, "canmanagecontent" => $canmanagecontent,
                                                 "canmanageads" => $canmanageads, "canmanagesettings" => $canmanagesettings, "canmanageusers" => $canmanageusers], "gid='{$mysidia->input->get("group")}'");
        } else {
            $checkBoxes = new LinkedHashMap();
            foreach ($permissions as $permission) {
                $checkBoxes->put(new MysString($permission), new CheckBox($mysidia->lang->{$permission}, $permission, "yes", $usergroup->$permission == "yes"));
            }
            $this->setField("checkBoxes", $checkBoxes);
        }
    }

    public function delete()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("group")) {
            $this->index();
            return;
        }
        $mysidia->db->delete("groups", "gid='{$mysidia->input->get("group")}'");
    }

    public function admin(): never
    {
        throw new InvalidActionException("global_action");
    }
}
