<?php

/**
 * The ItemTableHelper Class, extends from the TableHelper class.
 * It is a specific helper for tables that involves operations on items.
 * @category Resource
 * @package Helper
 * @author Hall of Famer
 * @copyright Mysidia Adoptables Script
 * @link http://www.mysidiaadoptables.com
 * @since 1.3.3
 * @todo Not much at this point.
 *
 */

class ItemTableHelper extends TableHelper
{
    /**
     * Constructor of ItemTableHelper Class, it simply serves as a wrap-up.
     * @access public
     * @return Void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The getUseForm method, constructs a use form for the inventory table.
     * @param Item  $item
     * @access public
     * @return Form|String
     */
    public function getUseForm(Item $item)
    {
        if ($item->consumable != "yes") {
            return "N/A";
        }
        $useForm = new FormBuilder("useform", "inventory/uses", "post");
        $useForm->setLineBreak(false);
        $useForm->buildComment("")
                ->buildPasswordField("hidden", "action", "uses")
                ->buildPasswordField("hidden", "itemname", $item->itemname)
                ->buildButton("Use", "use", "use");
        return $useForm;
    }

    /**
     * The getSellForm method, constructs a sell form for the inventory table.
     * @param Item  $item
     * @access public
     * @return Form|String
     */
    public function getSellForm(Item $item)
    {
        if ($item->category == "Key Items") {
            return "N/A";
        }
        $sellForm = new FormBuilder("sellform", "inventory/sell", "post");
        $sellForm->setLineBreak(false);
        $sellForm->buildComment("")
                 ->buildPasswordField("hidden", "action", "sell")
                 ->buildPasswordField("hidden", "itemname", $item->itemname);

        $quantity = new TextField("quantity");
        $quantity->setSize(3);
        $quantity->setMaxLength(3);
        $quantity->setLineBreak(false);

        $sell = new Button("Sell", "sell", "sell");
        $sell->setLineBreak(false);

        $sellForm->add($quantity);
        $sellForm->add($sell);
        return $sellForm;
    }

    /**
     * The getTossForm method, constructs a toss form for the inventory table.
     * @param Item  $item
     * @access public
     * @return Form|String
     */
    public function getTossForm(Item $item)
    {
        if ($item->category == "Key Items") {
            return "N/A";
        }
        $tossForm = new FormBuilder("tossform", "inventory/toss", "post");
        $tossForm->setLineBreak(false);
        $tossForm->buildComment("")
                 ->buildPasswordField("hidden", "action", "toss")
                 ->buildPasswordField("hidden", "itemname", $item->itemname)
                 ->buildButton("Toss", "toss", "toss");
        return $tossForm;
    }

    /**
     * Magic method __toString for ItemTableHelper class, it reveals that the object is an item table helper.
     * @access public
     * @return String
     */
    public function __toString(): string
    {
        return (string) new String("This is an instance of Mysidia ItemTableHelper class.");
    }
}
