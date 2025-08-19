<?php

use Resource\Native\MysString;

class PrivateItem extends Item
{
    // The PrivateItem class, which defines functionalities for items that belong to specific users

    public $iid;
    public $owner;
    public $quantity;
    public $status;

    public function __construct($iteminfo, $itemowner = "")
    {
        // the item is an owned item in user inventory, so retrieve database info to assign properties
        $mysidia = Registry::get("mysidia");

        $fetchmode = (is_numeric($iteminfo)) ? "iid" : "itemname";
        $whereclause = ($fetchmode == "iid") ? "{$fetchmode} = '{$iteminfo}'" : "{$fetchmode} ='{$iteminfo}' and owner = '{$itemowner}'";
        $row = $mysidia->db->select("inventory", [], $whereclause)->fetchObject();
        if (is_object($row)) {
            // loop through the anonymous object created to assign properties
            foreach ($row as $key => $val) {
                $this->$key = $val;
            }
            parent::__construct($this->itemname);
        } else {
            $this->iid = 0;
        }
    }

    public function getitem()
    {
        // This method checks if the item exists in inventory or not, not to be confused with parent class' getitem() class.
        $mysidia = Registry::get("mysidia");
        $stmt = $mysidia->db->select("inventory", [], "itemname ='{$this->itemname}' and owner ='{$this->owner}'");
        return $stmt->fetchObject();
    }

    public function getvalue($quantity = 0, $discount = 0.5)
    {
        // This method returns the cost of items.

        $value = $this->price * $quantity * $discount;
        return $value;
    }

    public function apply($adopt = "", $user = "")
    {
        // This method uses
        $mysidia = Registry::get("mysidia");
        require_once("functions/functions_items.php");

        if (is_numeric($adopt)) {
            $owned_adoptable = $mysidia->db->select("owned_adoptables", [], "aid ='{$adopt}'")->fetchObject();
        }
        if (!empty($user)) {
            $theuser = $mysidia->db->select("users", [], "username ='{$user}'")->fetchObject();
        }

        // Now we decide which function to call...
        $message = match ($this->function) {
            "Valuable" => items_valuable($this, $owned_adoptable),
            "Level1" => items_level1($this, $owned_adoptable),
            "Level2" => items_level2($this, $owned_adoptable),
            "Level3" => items_level3($this, $owned_adoptable),
            "Click1" => items_click1($this, $owned_adoptable),
            "Click2" => items_click2($this, $owned_adoptable),
            "Breed1" => items_breed1($this, $owned_adoptable),
            "Breed2" => items_breed2($this, $owned_adoptable),
            "Alts1" => items_alts1($this, $owned_adoptable),
            "Alts2" => items_alts2($this, $owned_adoptable),
            "Name1" => items_name1($this, $theuser),
            "Name2" => items_name2($this, $theuser),
            default => throw new ItemException("The item function is invalid"),
        };
        return new MysString($message);
    }

    public function add($owner, $quantity = 1)
    {

    }

    public function sell($quantity = 1, $owner = "")
    {
        // This method sells items from user inventory
        $mysidia = Registry::get("mysidia");

        $this->owner = (!empty($owner)) ? $owner : $this->owner;
        $earn = $this->getvalue($quantity);
        $newamount = $mysidia->user->money + $earn;

        if ($this->remove($quantity)) {
            $mysidia->db->update("users", ["money" => $newamount], "username = '{$this->owner}'");
            return true;
        } else {
            return false;
        }
    }

    public function toss($owner = "")
    {
        $this->remove($this->quantity);
        return true;
    }

    public function remove($quantity = 1, $owner = "")
    {
        // This method removes items from user inventory

        $mysidia = Registry::get("mysidia");
        $this->owner = (!empty($owner)) ? $owner : $this->owner;
        $newquantity = $this->quantity - $quantity;
        if (empty($this->quantity) or $newquantity < 0) {
            return false;
        } else {
            match ($newquantity) {
                0 => $mysidia->db->delete("inventory", "itemname='{$this->itemname}' and owner='{$this->owner}'"),
                default => $mysidia->db->update("inventory", ["quantity" => $newquantity], "itemname ='{$this->itemname}' and owner='{$this->owner}'"),
            };
            return true;
        }
    }

    public function checktarget($aid)
    {
        // This method checks if the item is usable
        $adopt = new OwnedAdoptable($aid);
        $id = $adopt->getID();
        $item_usable = false;
        switch ($this->target) {
            case "all":
                $item_usable = true;
                break;
            case "user":
                $item_usable = true;
                break;
            default:
                $target = explode(",", (string) $this->target);
                if (in_array($id, $target)) {
                    $item_usable = true;
                }
        }
        return $item_usable;
    }

    public function randomchance()
    {
        // This method returns the item image in standard html form
        $mysidia = Registry::get("mysidia");
        switch ($this->chance) {
            case 100:
                $item_usable = true;
                break;
            default:
                $temp = mt_rand(0, 99);
                $item_usable = ($temp < $this->chance) ? true : false;
        }
        return $item_usable;
    }
}
