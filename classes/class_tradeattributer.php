<?php

use Resource\Native\Objective;
use Resource\Native\Null;
use Resource\Collection\ArrayList;

class TradeAttributer extends Helper
{
    public function __construct(private readonly TradeSetting $settings, private Controller $controller)
    {
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController(Controller $controller)
    {
        $this->controller = $controller;
    }

    public function getField($key)
    {
        $this->controller->getField($key);
    }

    public function setField($key, Objective $value)
    {
        $this->controller->setField($key, $value);
    }

    public function setFields(HashMap $fields)
    {
        $this->controller->setFields($fields);
    }

    public function getOffer($tid = 0)
    {
        $mysidia = Registry::get("mysidia");
        $offer = new TradeOffer($tid, ($tid == 0) ? true : false);
        if ($mysidia->input->post("public") == "yes") {
            $offer->setType("public");
        } elseif ($mysidia->input->post("partial") == "yes") {
            $offer->setType("partial");
        } else {
            $offer->setType("private");
        }

        $offer->setSender($mysidia->user->username);
        $offer->setRecipient($mysidia->input->post("recipient"));
        $offer->setAdoptOffered(($mysidia->input->post("adoptOffered") == "none") ? null : $mysidia->input->post("adoptOffered"));
        $offer->setAdoptWanted(($mysidia->input->post("adoptWanted") == "none") ? null : $mysidia->input->post("adoptWanted"));
        $offer->setItemOffered(($mysidia->input->post("itemOffered") == "none") ? null : $mysidia->input->post("itemOffered"));
        $offer->setItemWanted(($mysidia->input->post("itemWanted") == "none") ? null : $mysidia->input->post("itemWanted"));
        $offer->setCashOffered($mysidia->input->post("cashOffered"));
        $offer->setMessage($mysidia->input->post("message"));
        $offer->setStatus(($this->settings->moderate == "enabled") ? "moderate" : "pending");
        $offer->setDate(new DateTime());
        return $offer;
    }

    public function setAttributes()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->get("param") == "tid") {
            if ($mysidia->input->action() == "publics") {
                $this->setPublicAttributes();
            } elseif ($mysidia->input->action() == "partials") {
                $this->setPartialAttributes();
            } else {
                $this->setPrivateAttributes();
            }
        } elseif ($mysidia->input->get("param") == "user") {
            $this->setUserAttributes();
        } elseif ($mysidia->input->get("param") == "adopt") {
            $this->setAdoptAttributes();
        } elseif ($mysidia->input->get("param") == "item") {
            $this->setItemAttributes();
        } else {
            $this->setNullAttributes();
        }
    }

    private function setCommonAttributes()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->get("param")) {
            $params = new ArrayList();
            $params->add($mysidia->input->get("id"));
            $this->setField("params", $params);
        }

        $stmt = $mysidia->db->select("owned_adoptables", ["name", "aid"], "owner = '{$mysidia->user->username}' AND tradestatus = 'fortrade'");
        $adoptOffered = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("adoptOffered", $adoptOffered);

        $stmt = $mysidia->db->select("inventory", ["itemname", "iid"], "owner = '{$mysidia->user->username}'");
        $itemOffered = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("itemOffered", $itemOffered);
    }

    private function setUserAttributes()
    {
        $mysidia = Registry::get("mysidia");
        $this->setCommonAttributes();
        $recipient = new Member($mysidia->input->get("id"));
        $this->setField("recipient", $recipient);

        $stmt = $mysidia->db->select("owned_adoptables", ["name", "aid"], "owner = '{$recipient->username}' AND tradestatus = 'fortrade'");
        $adoptWanted = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("adoptWanted", $adoptWanted);

        $stmt = $mysidia->db->select("inventory", ["itemname", "iid"], "owner = '{$recipient->username}'");
        $itemWanted = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("itemWanted", $itemWanted);
    }

    private function setAdoptAttributes()
    {
        $mysidia = Registry::get("mysidia");
        $this->setCommonAttributes();
        $recipient = $mysidia->db->select("owned_adoptables", ["owner"], "aid = '{$mysidia->input->get("id")}'")->fetchColumn();
        $recipient = new Member($recipient);
        $this->setField("recipient", $recipient);

        $stmt = $mysidia->db->select("owned_adoptables", ["name", "aid"], "owner = '{$recipient->username}' AND tradestatus = 'fortrade'");
        $adoptWanted = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("adoptWanted", $adoptWanted);

        $stmt = $mysidia->db->select("inventory", ["itemname", "iid"], "owner = '{$recipient->username}'");
        $itemWanted = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("itemWanted", $itemWanted);
    }

    private function setItemAttributes()
    {
        $mysidia = Registry::get("mysidia");
        $this->setCommonAttributes();
        $recipient = $mysidia->db->select("inventory", ["owner"], "iid = '{$mysidia->input->get("id")}'")->fetchColumn();
        $recipient = new Member($recipient);
        $this->setField("recipient", $recipient);

        $stmt = $mysidia->db->select("owned_adoptables", ["name", "aid"], "owner = '{$recipient->username}' AND tradestatus = 'fortrade'");
        $adoptWanted = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("adoptWanted", $adoptWanted);

        $stmt = $mysidia->db->select("inventory", ["itemname", "iid"], "owner = '{$recipient->username}'");
        $itemWanted = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("itemWanted", $itemWanted);
    }

    private function setNullAttributes()
    {
        $mysidia = Registry::get("mysidia");
        $this->setCommonAttributes();
        $this->setField("recipient", new Null());

        $stmt = $mysidia->db->select("adoptables", ["type", "id"]);
        $adoptWanted = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("adoptWanted", $adoptWanted);

        $stmt = $mysidia->db->select("items", ["itemname", "id"]);
        $itemWanted = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("itemWanted", $itemWanted);
    }

    private function setPrivateAttributes()
    {
        $mysidia = Registry::get("mysidia");
        $this->setCommonAttributes();
        $offer = new TradeOffer($mysidia->input->get("id"));
        $recipient = new Member($offer->getRecipient());
        $this->setField("recipient", $recipient);

        $stmt = $mysidia->db->select("owned_adoptables", ["name", "aid"], "owner = '{$recipient->username}' AND tradestatus = 'fortrade'");
        $adoptWanted = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("adoptWanted", $adoptWanted);

        $stmt = $mysidia->db->select("inventory", ["itemname", "iid"], "owner = '{$recipient->username}'");
        $itemWanted = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("itemWanted", $itemWanted);
    }

    private function setPublicAttributes()
    {
        $mysidia = Registry::get("mysidia");
        $offer = new TradeOffer($mysidia->input->get("id"));
        $recipient = new Member($offer->getSender());
        $this->setField("recipient", $recipient);

        $stmt = $mysidia->db->select("owned_adoptables", ["name", "aid"], $this->getPublicQueries("adopt", $offer->getAdoptWanted()));
        $adoptOffered = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("adoptOffered", $adoptOffered);

        $adoptWanted = $offer->getAdoptOffered();
        if ($adoptWanted  == null) {
            $adoptWanted = new Null();
        }
        $this->setField("adoptWanted", $adoptWanted);

        $stmt = $mysidia->db->select("inventory", ["itemname", "iid"], $this->getPublicQueries("item", $offer->getItemWanted()));
        $itemOffered = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("itemOffered", $itemOffered);

        $itemWanted = $offer->getItemOffered();
        if ($itemWanted  == null) {
            $itemWanted = new Null();
        }
        $this->setField("itemWanted", $itemWanted);
    }

    private function getPublicQueries($criterion, ArrayList $list = null)
    {
        $mysidia = Registry::get("mysidia");
        $whereClause = "owner = '{$mysidia->user->username}'";
        if (!$list) {
            return $whereClause;
        }

        $whereClause .= " AND (";
        $iterator = $list->iterator();
        while ($iterator->hasNext()) {
            $id = $iterator->next()->getValue();
            if ($criterion == "adopt") {
                $adopt = new Adoptable($id);
                $whereClause .= "type = '{$adopt->getType()}' OR ";
            } else {
                $item = new Item($id);
                $whereClause .= "itemname = '{$item->itemname}' OR ";
            }
        }
        $whereClause .= "0)";
        return $whereClause;
    }

    private function setPartialAttributes()
    {
        $mysidia = Registry::get("mysidia");
        $offer = new TradeOffer($mysidia->input->get("id"));
        $recipient = new Member($offer->getSender());
        $this->setField("recipient", $recipient);

        $stmt = $mysidia->db->select("owned_adoptables", ["name", "aid"], "owner = '{$mysidia->user->username}' AND tradestatus = 'fortrade'");
        $adoptOffered = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("adoptOffered", $adoptOffered);

        $stmt = $mysidia->db->select("owned_adoptables", ["name", "aid"], "owner = '{$recipient->username}' AND tradestatus = 'fortrade'");
        $adoptWanted = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("adoptWanted", $adoptWanted);

        $stmt = $mysidia->db->select("inventory", ["itemname", "iid"], "owner = '{$mysidia->user->username}'");
        $itemOffered = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("itemOffered", $itemOffered);

        $stmt = $mysidia->db->select("inventory", ["itemname", "iid"], "owner = '{$recipient->username}'");
        $itemWanted = ($stmt->rowcount() == 0) ? new Null() : $mysidia->db->fetchMap($stmt);
        $this->setField("itemWanted", $itemWanted);
    }
}
