<?php

use Resource\Native\Integer;

class OnlineController extends AppController
{
    public function index()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->systems->online != "enabled") {
            throw new NoPermissionException("The admin has turned off who's online feature for this site, please contact him/her for detailed information.");
        }
        $wol = new Online("members");
        $stmt = $mysidia->db->select("online", ["username"], "username != 'Visitor'");
        $this->setField("total", new Integer($wol->gettotal()));
        $this->setField("stmt", new DatabaseStatement($stmt));
    }
}
