<?php

use Resource\Native\MysObject;
use Resource\Native\Arrays;

final class Daycare extends MysObject
{
    private $adopts;
    private $total;
    private $settings;
    private $pagination = false;

    public function __construct()
    {
        $mysidia = Registry::get("mysidia");
        $this->settings = new DaycareSetting($mysidia->db);
        if ($this->settings->system == "disabled") {
            throw new DaycareException("system");
        }
    }

    public function getAdopts()
    {
        if (!$this->adopts) {
            $mysidia = Registry::get("mysidia");
            $conditions = $this->getConditions();
            $fetchMode = $this->getFetchMode($conditions);
            $stmt = $mysidia->db->select("owned_adoptables", ["aid"], $conditions.$fetchMode);
            if ($stmt->rowCount() == 0) {
                throw new DaycareException("empty");
            }
            $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $this->adopts = Arrays::fromArray($ids);
            $this->total = $this->adopts->getSize();
        }
        return $this->adopts;
    }

    private function getConditions()
    {
        $mysidia = Registry::get("mysidia");
        $conditions = "isfrozen != 'yes'";
        if (is_numeric($this->settings->level)) {
            $conditions .= " and currentlevel <= '{$this->settings->level}'";
        }
        if ($this->settings->species) {
            foreach ($this->settings->species as $species) {
                $conditions .= " and type != '{$species}'";
            }
        }
        if ($this->settings->owned != "yes") {
            $conditions .= " and owner != '{$mysidia->user->username}'";
        }
        return $conditions;
    }

    private function getFetchMode($conditions)
    {
        $mysidia = Registry::get("mysidia");
        if ($this->settings->display == "all") {
            $total = $mysidia->db->select("owned_adoptables", ["aid"], $conditions)->rowCount();
            $this->pagination = new Pagination($total, $this->settings->number, "levelup/daycare");
            $this->pagination->setPage($mysidia->input->get("page"));
            $fetchMode = " ORDER BY currentlevel LIMIT {$this->pagination->getLimit()},{$this->pagination->getRowsperPage()}";
        } else {
            $fetchMode = " ORDER BY RAND() DESC LIMIT {$this->settings->number}";
        }
        return $fetchMode;
    }

    public function getTotalAdopts()
    {
        if (!$this->total) {
            $this->getAdopts();
        }
        return $this->total;
    }

    public function getTotalRows()
    {
        return ceil($this->total / $this->settings->columns);
    }

    public function getTotalColumns()
    {
        return ($this->total < $this->settings->columns) ? $this->total : $this->settings->columns;
    }

    public function getPagination()
    {
        return $this->pagination;
    }

    public function getStats($adopt)
    {
        foreach ($this->settings->info as $info) {
            $method = "get".$info;
            $stats .= "{$info}: {$adopt->$method()}<br>";
        }
        return $stats;
    }
}
