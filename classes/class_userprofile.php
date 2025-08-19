<?php

use Resource\Collection\LinkedList;

class UserProfile extends Model
{
    // The user profile class, it has dependency over class Member and cannot exist on its own
    public $uid;
    public $username;
    protected $avatar;
    protected $bio;
    protected $color;
    protected $about;
    protected $favpet;
    protected $gender;
    protected $nickname;

    public function __construct($uid)
    {
        // Fetch the basic profile params for users

        $mysidia = Registry::get("mysidia");
        $row = $mysidia->db->select("users_profile", [], "uid ='{$uid}'")->fetchObject();
        // loop through the anonymous object created to assign properties
        foreach ($row as $key => $val) {
            $this->$key = $val;
        }
        // Successfully instantiate user profile object
    }

    public function formatusername()
    {
        $ccstats = isset($this->usergroup) ? cancp($this->usergroup) : cancp(0);
        $this->username = ($ccstats == "yes") ? "<img src='templates/icons/star.gif' /> {$this->username}" : $this->username;
        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function getBio()
    {
        return $this->bio;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function getFavpetID()
    {
        return $this->favpet;
    }

    public function getFavpetInfo()
    {
        return $this->about;
    }

    public function getFavpet()
    {
        if (is_numeric($this->favpet)) {
            $this->favpet = ($this->favpet == 0) ? new Comment("None Selected") : new Link("levelup/click/{$this->favpet}", new Image("levelup/siggy/{$this->favpet}"), true);
        }
        return $this->favpet;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getNickname()
    {
        return $this->nickname;
    }

    public function display($action = "", $data = "")
    {
        match ($action) {
            "vmessages" => $this->vmessages(),
            "aboutme" => $this->aboutme(),
            "adopts" => $this->showalladopts(),
            "friends" => $this->getfriends($data),
            "contactinfo" => $this->contactinfo($data),
            default => throw new Exception("Invalid profile tab..."),
        };
    }

    private function vmessages()
    {
        $mysidia = Registry::get("mysidia");
        $document = $mysidia->frame->getDocument();
        $stmt = $mysidia->db->select("visitor_messages", [], "touser = '{$mysidia->input->get("user")}' ORDER BY vid DESC LIMIT 0, 15");
        if ($stmt->rowCount() == 0) {
            return;
        }
        $vmList = new TableBuilder("vmessages", 800, false);
        $vmList->setHelper(new MessageTableHelper());
        while ($vmessage = $stmt->fetchObject()) {
            $sender = $mysidia->db->join("users_profile", "users_profile.uid = users.uid")
                                  ->select("users", [], constant("PREFIX")."users.username = '{$vmessage->fromuser}'")
                                  ->fetchObject();
            $cells = new LinkedList();
            $cells->add(new TCell($vmList->getHelper()->getAvatarImage($sender->avatar)));
            $cells->add(new TCell($vmList->getHelper()->getVisitorMessage($vmessage)));
            if (($mysidia->user instanceof Admin) or ($mysidia->user->username == $vmessage->fromuser)) {
                $cells->add(new TCell($vmList->getHelper()->getManageActions($vmessage->vid)));
            }
            $vmList->buildRow($cells);
        }
        $document->add($vmList);
    }

    private function aboutme()
    {
        $mysidia = Registry::get("mysidia");
        $document = $mysidia->frame->getDocument();
        $title = new Comment($mysidia->lang->basic.$mysidia->input->get("user"));
        $title->setBold();
        $title->setUnderlined();
        $membersince = $mysidia->db->select("users", ["membersince"], "username = '{$mysidia->input->get("user")}'")->fetchColumn();
        $bio = stripslashes((string) $this->bio);
        $basicinfo = "<br><strong>Member Since:</strong> {$membersince}<br>
				    Gender: {$this->gender}<br>
				    Favorite Color: {$this->color}<br>
				    Nickname: {$this->nickname}<br>
				    Bio: {$bio}";

        $document->add($title);
        $document->add(new Image($this->avatar, "avatar", 100));
        $document->add(new Comment($basicinfo));
    }

    private function showalladopts()
    {
        $mysidia = Registry::get("mysidia");
        $document = $mysidia->frame->getDocument();
        $spotlight = new Comment(".:AdoptSpotlight:.");
        $spotlight->setHeading(2);

        $document->add($spotlight);
        $document->add($this->favpet);
        $document->add(new Comment($this->about));

        $title = new Comment("{$mysidia->input->get("user")}'s Pets:");
        $title->setBold();
        $title->setUnderlined();
        $document->add($title);

        $stmt = $mysidia->db->select("owned_adoptables", ["aid"], "owner = '{$mysidia->input->get("user")}'");
        while ($id = $stmt->fetchColumn()) {
            $adopt = new OwnedAdoptable($id);
            $document->add(new Link("levelup/click/{$adopt->getAdoptID()}", $adopt->getImage("gui")));
        }
    }

    private function getfriends($user)
    {
        $mysidia = Registry::get("mysidia");
        $document = $mysidia->frame->getDocument();
        $friendlist = new Friendlist($user);
        $document->add(new Comment("{$user->username} currently have {$friendlist->gettotal()} friends."));
        $friendlist->display();
    }

    private function contactinfo($contacts)
    {
        $mysidia = Registry::get("mysidia");
        $document = $mysidia->frame->getDocument();
        $member = new Member($mysidia->input->get("user"));
        $document->add(new Image("templates/icons/web.gif", "web"));
        $document->add(new Comment($contacts->website));
        $document->add(new Image("templates/icons/facebook.gif", "facebook"));
        $document->add(new Comment($contacts->facebook));
        $document->add(new Image("templates/icons/twitter.gif", "twitter"));
        $document->add(new Comment($contacts->twitter));
        $document->add(new Image("templates/icons/aim.gif", "aim"));
        $document->add(new Comment($contacts->aim));
        $document->add(new Image("templates/icons/msn.gif", "msn"));
        $document->add(new Comment($contacts->msn));
        $document->add(new Image("templates/icons/yahoo.gif", "yahoo"));
        $document->add(new Comment($contacts->yim));
        $document->add(new Image("templates/icons/skype.gif", "skype"));
        $document->add(new Comment($contacts->skype));
        $document->add(new Image("templates/icons/title.gif", "Write a PM"));
        $document->add(new Link("messages/newpm/{$mysidia->input->get("user")}", "Send {$mysidia->input->get("user")} a Private Message", true));
        $document->add(new Image("templates/icons/fr.gif", "Send a Friend Request"));
        $document->add(new Link("friends/request/{$member->uid}", "Send {$mysidia->input->get("user")} a Friend Request", true));
        $document->add(new Image("templates/icons/trade.gif", "Make a Trade Offer"));
        $document->add(new Link("trade/offer/user/{$member->uid}", "Make {$mysidia->input->get("user")} a Trade Offer"));
    }

    protected function save($field, $value)
    {

    }
}
