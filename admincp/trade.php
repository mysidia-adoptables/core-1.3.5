<?php

use Resource\Native\MysString;

class ACPTradeController extends AppController
{
    public const PARAM = "tid";
    private $settings;

    public function __construct()
    {
        parent::__construct();
        $this->settings = new TradeSetting();
        $mysidia = Registry::get("mysidia");
        if ($mysidia->usergroup->getpermission("canmanagesettings") != "yes") {
            throw new NoPermissionException("You do not have permission to manage trade.");
        }
    }

    public function index()
    {
        parent::index();
        $mysidia = Registry::get("mysidia");
        $total = $mysidia->db->select("trade")->rowCount();
        if ($total == 0) {
            throw new InvalidIDException("default_none");
        }
        $pagination = new Pagination($total, $mysidia->settings->pagination, "admincp/trade");
        $pagination->setPage($mysidia->input->get("page"));
        $stmt = $mysidia->db->select("trade", [], "1 LIMIT {$pagination->getLimit()},{$pagination->getRowsperPage()}");
        $this->setField("pagination", $pagination);
        $this->setField("stmt", new DatabaseStatement($stmt));
    }

    public function add()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->post("submit")) {
            $this->dataValidate();
            $mysidia->db->insert("trade", ["tid" => null, "type" => $mysidia->input->post("type"), "sender" => $mysidia->input->post("sender"), "recipient" => $mysidia->input->post("recipient"),
                                                "adoptoffered" => $mysidia->input->post("adoptOffered"), "adoptwanted" => $mysidia->input->post("adoptWanted"), "itemoffered" => $mysidia->input->post("itemOffered"), "itemwanted" => $mysidia->input->post("itemWanted"),
                                                "cashoffered" => $mysidia->input->post("cashOffered"), "message" => stripslashes((string) $mysidia->input->post("message")), "status" => $mysidia->input->post("status"), "date" => $mysidia->input->post("date")]);
        }
    }

    public function edit()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("tid")) {
            // A trade offer has yet been selected, return to the index page.
            $this->index();
            return;
        } elseif ($mysidia->input->post("submit")) {
            $this->dataValidate();
            $mysidia->db->update("trade", ["type" => $mysidia->input->post("type"), "sender" => $mysidia->input->post("sender"), "recipient" => $mysidia->input->post("recipient"),
                                                "adoptoffered" => $mysidia->input->post("adoptOffered"), "adoptwanted" => $mysidia->input->post("adoptWanted"), "itemoffered" => $mysidia->input->post("itemOffered"), "itemwanted" => $mysidia->input->post("itemWanted"),
                                                "cashoffered" => $mysidia->input->post("cashOffered"), "message" => stripslashes((string) $mysidia->input->post("message")), "status" => $mysidia->input->post("status"), "date" => $mysidia->input->post("date")], "tid = '{$mysidia->input->get("tid")}'");
            return;
        } else {
            $trade = $mysidia->db->select("trade", [], "tid='{$mysidia->input->get("tid")}'")->fetchObject();
            if (!is_object($trade)) {
                throw new InvalidIDException("nonexist");
            }
            $this->setField("trade", new DataMysObject($trade));
        }
    }

    public function delete()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("tid")) {
            // A trade offer has yet been selected, return to the index page.
            $this->index();
            return;
        }
        $mysidia->db->delete("trade", "tid='{$mysidia->input->get("tid")}'");
    }

    public function moderate()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->get("tid")) {
            // A trade offer has been select for moderation, let's go over it!
            $tradeOffer = new TradeOffer($mysidia->input->get("tid"));
            if ($mysidia->input->post("submit")) {
                $trade = new Trade($tradeOffer, $this->settings);
                $status = $mysidia->input->post("status");
                $trade->moderate($status);
                $this->setField("status", new MysString($status));
                return;
            }
            $this->setField("trade", $tradeOffer);
            $this->setField("tradeHelper", new TradeHelper($this->settings, $this));
            return;
        }
        $stmt = $mysidia->db->select("trade", [], "status = 'moderate'");
        $this->setField("stmt", new DatabaseStatement($stmt));
    }

    public function settings()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->post("submit")) {
            $settings = ['system', 'multiple', 'partial', 'public', 'species', 'interval',
                              'number', 'duration', 'tax', 'usergroup', 'item', 'moderate'];
            foreach ($settings as $name) {
                if ($mysidia->input->post($name) != ($this->settings->{$name})) {
                    $mysidia->db->update("trade_settings", ["value" => $mysidia->input->post($name)], "name='{$name}'");
                }
            }
            return;
        }
        $this->setField("tradeSettings", $this->settings);
    }

    private function dataValidate()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->post("sender")) {
            throw new BlankFieldException("sender");
        }
        if (!$mysidia->input->post("recipient") and $mysidia->input->post("type") != "public") {
            throw new BlankFieldException("recipient");
        }
        if ($mysidia->input->post("recipient") and $mysidia->input->post("type") == "public") {
            throw new BlankFieldException("public");
        }
        if (!$mysidia->input->post("adoptOffered") and !$mysidia->input->post("adoptWanted") and !$mysidia->input->post("itemOffered") and !$mysidia->input->post("itemWanted") and !$mysidia->input->post("cashOffered")) {
            throw new BlankFieldException("blank");
        }
        return true;
    }
}
