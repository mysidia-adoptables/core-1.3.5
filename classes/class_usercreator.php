<?php

use Resource\Native\MysObject;

abstract class UserCreator extends MysObject implements Creator
{
    // The abstract factory class UserCreator

    abstract public function create();
    abstract public function massproduce();

    public static function logincheck()
    {
        $mysidia = Registry::get("mysidia");
        //Check for cookie
        if (!$mysidia->cookies->getcookies("mysuid") or !$mysidia->cookies->getcookies("myssession")) {
            return false;
        } else {
            $uid = secure($mysidia->cookies->getcookies("mysuid"));
            $session = secure($mysidia->cookies->getcookies("myssession"));

            //Run login operation
            $luser = $mysidia->db->select("users", ["uid", "session"], "uid = '{$uid}'")->fetchObject();
            $luid = $luser->uid;
            $lsess = $luser->session;

            if ($uid == $luid and $session == $lsess) {
                return true;
            } else {
                if (isset($_COOKIE['mysuid'])) {
                    setcookie("mysuid", (string) $uid, ['expires' => time() - 10]);
                }
                if (isset($_COOKIE['myssession'])) {
                    setcookie("myssession", (string) $session, ['expires' => time() - 10]);
                }
                return false;
            }
        }
        // End of login check
    }

    protected function getusergroup($userinfo)
    {
        $mysidia = Registry::get("mysidia");
        $whereclause = (is_numeric($userinfo)) ? "uid ='{$userinfo}'" : "username ='{$userinfo}'";
        $usergroup = $mysidia->db->select("users", ["usergroup"], $whereclause)->fetchColumn();
        if (empty($usergroup)) {
            throw new Exception('Invalid Usergroup, cannot instantiate user object');
        }
        return $usergroup;
    }

    protected function getgroupcategory()
    {
        $mysidia = Registry::get("mysidia");
        $category = $mysidia->db->select("groups", ["category"], "uid ='{$uid}'")->fetchColumn();
        if (empty($category)) {
            throw new Exception('Invalid Usergroup Category, cannot instantiate user object');
        }
        return $category;
    }

    protected function getidentity()
    {
        $spiderip = [];
        if (in_array($_SERVER['REMOTE_ADDR'], $spiderip)) {
            $identity = "Spider";
        } else {
            $identity = "Guest";
        }
        return $identity;
    }

}
