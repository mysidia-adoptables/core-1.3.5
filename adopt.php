<?php

use Resource\Native\Integer;
use Resource\Native\MysString;
use Resource\Native\Arrays;
use Resource\Native\Null;

class AdoptController extends AppController
{
    public function __construct()
    {
        parent::__construct("member");
        $mysidia = Registry::get("mysidia");
        if ($mysidia->systems->adopts != "enabled") {
            throw new NoPermissionException("The admin has turned off adoption feature for this site, please contact him/her for detailed information.");
        }
        if ($mysidia->usergroup->getpermission("canadopt") != "yes") {
            throw new NoPermissionException("permission");
        }
    }

    public function index()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->post("submit")) {
            $this->access = "member";
            $this->handleAccess();
            $id = $mysidia->input->post("id");
            if ($mysidia->session->fetch("adopt") != 1 or !$id) {
                throw new InvalidIDException("global_id");
            }

            $adopt = new Adoptable($id);
            $conditions = $adopt->getConditions();
            if (!$conditions->checkConditions()) {
                throw new NoPermissionException("condition");
            }

            $name = (!$mysidia->input->post("name")) ? $adopt->getType() : $mysidia->input->post("name");
            $code = $adopt->getCode();
            $gender = $adopt->getGender();
            $mysidia->db->insert("owned_adoptables", ["aid" => null, "type" => $adopt->getType(), "name" => $name, "owner" => $mysidia->user->username, "currentlevel" => 0, "totalclicks" => 0, "code" => $code,
                                                           "imageurl" => null, "alternate" => 0, "tradestatus" => 'fortrade', "isfrozen" => 'no', "gender" => $gender, "offsprings" => 0, "lastbred" => 0]);

            $aid = $mysidia->db->select("owned_adoptables", ["aid"], "code='{$code}' and owner='{$mysidia->user->username}'")->fetchColumn();
            $this->setField("aid", new Integer($aid));
            $this->setField("name", new MysString($name));
            $this->setField("eggImage", new MysString($adopt->getEggImage()));
            return;
        }

        $mysidia->session->assign("adopt", 1, true);
        $ids = $mysidia->db->select("adoptables", ["id"], "shop='none'")->fetchAll(PDO::FETCH_COLUMN);
        $total = ($ids) ? count($ids) : 0;

        if ($total == 0) {
            throw new InvalidActionException("adopt_none");
        } else {
            $adopts = new Arrays($total);
            $available = 0;

            foreach ($ids as $id) {
                $adopt = new Adoptable($id);
                $conditions = $adopt->getConditions();
                if ($conditions->checkConditions()) {
                    $adopts[$available++] = $adopt;
                }
            }

            if ($available == 0) {
                throw new InvalidActionException("adopt_none");
            } else {
                $adopts->setSize($available);
            }
        }
        $this->setField("adopts", $adopts);
    }
}
