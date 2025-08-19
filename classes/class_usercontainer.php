<?php

use Resource\Native\MysObject;

abstract class UserContainer extends MysObject implements Container
{
    // The abstract UserContainer class

    public function getcreator($fetchmode = "Members")
    {
        // The UserContainer usually consists of users

        return match ($fetchmode) {
            "Members" => new MemberCreator(),
            "Visitors" => new VisitorCreator(),
            default => false,
        };
    }

    abstract public function gettotal();
}
