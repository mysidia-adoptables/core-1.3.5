<?php

/**
 * The GlobalSystem Class, extending from the abstract Setting class.
 * It acts as a wrapper for all subsystem settings available throughout the site.
 * An instance of GlobalSystem class is generated upon Mysidia system object's creation.
 * This specific instance is available from Registry, just like any other Mysidia core objects.
 * Similar to class File and Database, it does not extend abstract Core class.
 * GlobalSystem is a final class, no child class shall derive from it.
 * @category Resource
 * @package Core
 * @author Hall of Famer
 * @copyright Mysidia Adoptables Script
 * @link http://www.mysidiaadoptables.com
 * @since 1.3.5
 * @todo Re-structure database table prefix_systems.
 */

final class GlobalSystem extends Setting
{
    /**
     * The site property, defines if the main site is turned on/off.
     * @access public
     * @var String
    */
    public $site;

    /**
     * The adopts property, defines if adoption system is enabled.
     * @access public
     * @var String
    */
    public $adopts;

    /**
     * The friends property, defines if friend system is enabled.
     * @access public
     * @var String
    */
    public $friends;

    /**
     * The items property, defines if item system is enabled.
     * @access public
     * @var String
    */
    public $items;

    /**
     * The messages property, defines if private message system is enabled.
     * @access public
     * @var String
    */
    public $messages;

    /**
     * The online property, defines if who's online feature is enabled.
     * @access public
     * @var String
    */
    public $online;

    /**
     * The promo property, defines if promocode feature is enabled.
     * @access public
     * @var String
    */
    public $promo;

    /**
     * The shops property, defines if shop system is enabled.
     * @access public
     * @var String
    */
    public $shops;

    /**
     * The shoutbox property, defines if shoutbox feature is enabled.
     * @access public
     * @var String
    */
    public $shoutbox;

    /**
     * The vmessages property, defines if visitor message system is enabled.
     * @access public
     * @var String
    */
    public $vmessages;


    /**
     * Constructor of GlobalSetting Class, it initializes basic setting parameters.
     * @param Database  $db
     * @access public
     * @return Void
     */
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    /**
     * The fetch method, returns all fields of GlobalSystem object by fetching information from database.
     * @access public
     * @return Void
     */
    public function fetch($db)
    {
        $stmt = $db->select("systems", []);
        while ($row = $stmt->fetchObject()) {
            $property = $row->name;
            $this->$property = $row->value;
        }
    }

    /**
     * The set method, set a field of GlobalSystem object with a specific value.
     * @param String  $property
     * @param String  $value
     * @access public
     * @return Void
     */
    public function set($property, $value)
    {
        $this->$property = $value;
    }
}
