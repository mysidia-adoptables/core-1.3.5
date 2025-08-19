<?php

// File ID: functions_items.php
// Purpose: Provides specific functions defined for items

function items_valuable($item, $adopt)
{
    $note = "The item {$item->itemname} is a valuable item, which cannot be used on any adoptable but may sell a good deal of money.";
    return $note;
}

function items_level1($item, $adopt)
{
    $mysidia = Registry::get("mysidia");
    $newlevel = $adopt->currentlevel + $item->value;
    $lev = $mysidia->db->select("levels", [], "adoptiename='{$adopt->type}' and thisislevel ='{$newlevel}'")->fetchObject();

    //Check if the adoptable's level is already at maximum.
    if (!is_object($lev)) {
        // object not created, the level is already at maximum.
        $note = "Unfortunately, your selected adoptable's level cannot be raised by using item {$item->itemname}.";
    } else {
        //Update item quantity...
        $delitem = $item->remove();
        //Execute the script to update adoptable's level and clicks.
        $mysidia->db->update("owned_adoptables", ["currentlevel" => $newlevel, "totalclicks" => $lev->requiredclicks], "aid ='{$adopt->aid}' and owner='{$item->owner}'");
        $note = "Congratulations, the item {$item->itemname} raised your adoptable's level by {$item->value}";
    }
    return $note;
}

function items_level2($item, $adopt)
{
    $mysidia = Registry::get("mysidia");
    $newlevel = $item->value;
    $lev = $mysidia->db->select("levels", [], "adoptiename='{$adopt->type}' and thisislevel ='{$newlevel}'")->fetchObject();

    //Check if the adoptable's level is already at maximum.
    if (!is_object($lev)) {
        // object not created, the level is already at maximum.
        $note = "Unfortunately, your selected adoptable's level cannot be raised by using item {$item->itemname}.";
    } else {
        //Update item quantity...
        $delitem = $item->remove();
        //Execute the script to update adoptable's level and clicks.
        $mysidia->db->update("owned_adoptables", ["currentlevel" => $newlevel, "totalclicks" => $lev->requiredclicks], "aid ='{$adopt->aid}' and owner='{$item->owner}'");
        $note = "Congratulations, the item {$item->itemname} increases your adoptable's level to {$item->value}";
    }
    return $note;
}

function items_level3($item, $adopt)
{
    $mysidia = Registry::get("mysidia");
    //Update item quantity...
    $delitem = $item->remove();
    //Execute the script to update adoptable's level and clicks.
    $mysidia->db->update("owned_adoptables", ["currentlevel" => 0, "totalclicks" => 0, "imageurl" => null, "alternate" => 0], "aid ='{$adopt->aid}' and owner='{$item->owner}'");
    $note = "Congratulations, the item {$item->itemname} has reset the level and clicks of your adoptable.";
    return $note;
}

function items_click1($item, $adopt)
{
    $mysidia = Registry::get("mysidia");
    $newclicks = $adopt->totalclicks + $item->value;
    $mysidia->db->update("owned_adoptables", ["totalclicks" => $newclicks], "aid='{$adopt->aid}'and owner='{$item->owner}'");
    $note = "By using {$item->itemname}, the adoptable's total number of clicks has raised by {$item->value}<br>";
    //Now lets check if the adoptable has reached a new level.

    $ownedAdopt = new OwnedAdoptable($adopt->aid);
    if ($ownedAdopt->hasNextLevel()) {
        //new level exists, time to check if the total clicks have reached required minimum clicks for next level.
        $nextLevel = $ownedAdopt->getNextLevel();
        $requiredClicks = $nextLevel->getRequiredClicks();
        if ($newclicks >= $requiredClicks and $requiredClicks != 0 and $requiredClicks != "") {
            // We need to level this adoptable up...
            $ownedAdopt->setCurrentLevel($nextLevel->getLevel(), "update");
            $note .= "And moreover, it has gained a new level!";
        }
    }
    //Update item quantity...
    $delitem = $item->remove();
    return $note;
}

function items_click2($item, $adopt)
{
    $mysidia = Registry::get("mysidia");
    $newclicks = $item->value;
    $mysidia->db->update("owned_adoptables", ["totalclicks" => $newclicks], "aid='{$adopt->aid}'and owner='{$item->owner}'");
    $note = "By using {$item->itemname}, the adoptable's total number of clicks has raised by {$item->value}<br>";
    //Now lets check if the adoptable has reached a new level.

    $ownedAdopt = new OwnedAdoptable($adopt->aid);
    if ($ownedAdopt->hasNextLevel()) {
        //new level exists, time to check if the total clicks have reached required minimum clicks for next level.
        $nextLevel = $ownedAdopt->getNextLevel();
        $requiredClicks = $nextLevel->getRequiredClicks();
        if ($newclicks >= $requiredClicks and $requiredClicks != 0 and $requiredClicks != "") {
            // We need to level this adoptable up...
            $ownedAdopt->setCurrentLevel($nextLevel->getLevel(), "update");
            $note .= "And moreover, it has gained a new level!";
        }
    }

    //Update item quantity...
    $delitem = $item->remove();
    return $note;
}

function items_click3($item, $adopt)
{
    $mysidia = Registry::get("mysidia");
    $date = date('Y-m-d');
    $mysidia->db->delete("vote_voters", "adoptableid = '{$adopt->aid}' and date='{$date}'");
    //Update item quantity...
    $delitem = $item->remove();
    $note = "By using item {$item->name}, you have make your adoptables eligible for clicking by everyone again!";
    return $note;
}

function items_breed1($item, $adopt)
{
    $mysidia = Registry::get("mysidia");
    // Update the lastbred info.
    $mysidia->db->update("owned_adoptables", ["lastbred" => 0], "aid ='{$adopt->aid}' and owner='{$item->owner}'");
    $note = "The item has been successfully used on your adoptable, it can breed again!<br>";
    //Update item quantity...
    $delitem = $item->remove(1, $item->owner);
    return $note;
}

function items_breed2($item, $adopt)
{
    $mysidia = Registry::get("mysidia");
    // Note this function exists but is not useful until Mys v1.3.2, when adoptables can carry/attach items.
    $mysidia->db->update("owned_adoptables", ["lastbred" => 0], "aid ='{$adopt->aid}' and owner='{$item->owner}'");
    $note = "The item has been successfully used on your adoptable, it can breed again!<br>";
    //Update item quantity...
    $delitem = $item->remove();
    return $note;
}

function items_alts1($item, $adopt)
{
    $mysidia = Registry::get("mysidia");
    $alt = $mysidia->db->select("alternates", [], "alid ='{$item->value}'")->fetchObject();
    if ($adopt->type != $alt->adopt || !$adopt->currentlevel < $alt->level) {
        $note = "The item can only be used for adoptable {$alt->adopt} at level {$alt->level} or above...<br>";
    } else {
        $mysidia->db->update("owned_adoptables", ["imageurl" => $alt->image, "alternate" => $alt->alid], "aid ='{$adopt->aid}' and owner='{$item->owner}'");
        $note = "Your adoptable {$adopt->name} has gotten a new alternate form.";
        $delitem = $item->remove();
    }
    return $note;
}

function items_alts2($item, $adopt)
{
    $mysidia = Registry::get("mysidia");
    $ownedAdopt = new OwnedAdoptable($adopt->aid);
    if ($ownedAdopt->getAdoptID() != $item->value) {
        $note = "The item cannot be used for your adoptable since it is not designed for adoptable type {$ownedAdopt->getType()}...<br>";
    } else {
        $ownedAdopt->updateAlternate("update");
        $note = "Your adoptable {$adopt->name} has gotten a new alternate form.";
        $delitem = $item->remove();
    }
    return $note;
}

function items_name1($item, $adopt)
{
    $note = "umm just realized that people can change adoptables names freely, will have to think about it later.";
    return $note;
}

function items_name2($item, $adopt)
{
    $note = "For now the items can only be used on adoptables, so user-based item usage will be implemented later.";
    return $note;
}

function items_recipe($item, $adopt)
{
    $note = "The item {$item->itemname} is a recipe item, which cannot be used on any adoptable and can only be useful if you are performing alchemy.";
    return $note;
}
