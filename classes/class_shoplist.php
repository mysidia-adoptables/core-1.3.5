<?php

use Resource\Native\MysObject;
use Resource\Native\MysString;
use Resource\Collection\LinkedList;
use Resource\Collection\LinkedHashMap;

class Shoplist extends MysObject implements Container
{
    // The Inventory Class, which acts as a container of items

    public $type;
    public $shops;
    protected $total = 0;
    protected $fetchmode = "visible";

    public function __construct($type = "", $fetchmode = "")
    {
        // Fetch the database info into object property

        $this->type = ($type == "adoptshop") ? "adoptshop" : "itemshop";
        if (!empty($fetchmode)) {
            $this->fetchmode = $fetchmode;
        }
        $this->shops = $this->getshops($this->fetchmode);
        $this->total = ($this->shops instanceof LinkedHashMap) ? $this->shops->size() : 0;
    }

    public function getshops($fetchmode = "")
    {
        // This method acquires all iids of items owned by the user.
        if (!$this->shops) {
            // The iids have yet to be loaded, lets acquire their info from database
            $mysidia = Registry::get("mysidia");

            $whereclause = $this->setfetchmode($fetchmode);
            $stmt = $mysidia->db->select("shops", ["shopname", "shoptype"], $whereclause);
            if ($stmt->rowCount() == 0) {
                return null;
            }
            $shops = new LinkedHashMap();

            while ($shop = $stmt->fetchObject()) {
                $shops->put(new MysString($shop->shopname), new MysString($shop->shoptype));
            }
            return $shops;
        } else {
            return $this->shops;
        }
    }

    public function gettotal()
    {
        // This method returns the total number of shops available on the site
        return $this->total;
    }

    protected function setfetchmode($fetchmode)
    {
        // This method determines the fetchmode of shoplist
        $whereclause = "shoptype = '{$this->type}' ";
        match ($fetchmode) {
            "visible" => $whereclause .= "and status!='invisible'",
            "open" => $whereclause .= "and status='open'",
            default => $whereclause .= "",
        };
        // End of Switch Statement
        return $whereclause;
    }

    public function createshop($shopname)
    {
        // This method serves as creator function for instantiating shops
        $shoptype = $this->shops->get($shopname);
        return match ($shoptype) {
            "itemshop" => new Itemshop($shopname->getValue()),
            "adoptshop" => new Adoptshop($shopname->getValue()),
            default => false,
        };
        // End of Switch statement
    }

    public function getshopimage($imageurl)
    {
        return new Image($imageurl);
    }

    public function iterator()
    {
        return $this->shops->iterator();
    }

    public function display()
    {
        // This method returns a list of shops available
    }
}
