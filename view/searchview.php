<?php

use Resource\Collection\LinkedList;

class SearchView extends View
{
    #[\Override]
    public function index()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;
        $document->setTitle($this->lang->title);
        $document->addLangvar($this->lang->default);
        $document->add(new Link("search/user", "Search for Users", true));
        $document->add(new Link("search/adopt", "Search for Adoptables", true));
        $document->add(new Link("search/item", "Search for Items", true));
        $document->add(new Link("search/page", "Search for Pages(Not available now)", true));
    }

    public function user()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;
        $document->setTitle($this->lang->user);

        if ($mysidia->input->post("submit")) {
            $userList = $this->getField("userList");
            $iterator = $userList->iterator();
            $searchTable = new TableBuilder("searchresult");
            $searchTable->setAlign(new Align("center"));
            $searchTable->buildHeaders("ID", "Name", "Email", "Usergroup", "Joindate", "Befriend", "Trade");
            $searchTable->setHelper(new SearchTableHelper());

            while ($iterator->hasNext()) {
                $user = $iterator->next();
                $cells = new LinkedList();
                $cells->add(new TCell($user->uid));
                $cells->add(new TCell($searchTable->getHelper()->getUserProfile($user->username)));
                $cells->add(new TCell($user->getemail()));
                $cells->add(new TCell($searchTable->getHelper()->getUsergroup($user->usergroup->gid)));
                $cells->add(new TCell($user->membersince));
                $cells->add(new TCell($searchTable->getHelper()->getFriendRequest($user->uid)));
                $cells->add(new TCell($searchTable->getHelper()->getTradeOffer($user->uid)));
                $searchTable->buildRow($cells);
            }
            $document->add($searchTable);
            return;
        }

        $searchForm = new FormBuilder("searchform", "user", "post");
        $searchForm->buildComment("Username: ", false)
                   ->buildTextField("name")
                   ->buildComment("Email: ", false)
                   ->buildTextField("email");

        $groupMap = $this->getField("groupMap");
        $usergroups = new DropdownList("group");
        $usergroups->add(new Option("None Selected", "none"));
        $usergroups->fill($groupMap);
        $searchForm->add(new Comment("Usergroup: ", false));
        $searchForm->add($usergroups);

        $searchForm->buildComment("Birthday:", false)
                   ->buildTextField("birthday")
                   ->buildComment("JoinDate:", false)
                   ->buildTextField("joindate")
                   ->buildButton("Search", "submit", "submit");
        $document->add($searchForm);
    }

    public function adopt()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;
        $document->setTitle($this->lang->adopt);
        if ($mysidia->input->post("submit")) {
            $adoptList = $this->getField("adoptList");
            $iterator = $adoptList->iterator();
            $searchTable = new TableBuilder("searchresult");
            $searchTable->setAlign(new Align("center"));
            $searchTable->buildHeaders("ID", "Name", "Type", "Owner", "Level", "Gender", "Trade");
            $searchTable->setHelper(new SearchTableHelper());

            while ($iterator->hasNext()) {
                $adopt = $iterator->next();
                $cells = new LinkedList();
                $cells->add(new TCell($adopt->getAdoptID()));
                $cells->add(new TCell($searchTable->getHelper()->getAdoptName($adopt->getAdoptID(), $adopt->getName())));
                $cells->add(new TCell($adopt->getType()));
                $cells->add(new TCell($searchTable->getHelper()->getUserProfile($adopt->getOwner())));
                $cells->add(new TCell($adopt->getCurrentLevel()));
                $cells->add(new TCell($searchTable->getHelper()->getGenderImage($adopt->getGender())));
                $cells->add(new TCell($searchTable->getHelper()->getTradeStatus($adopt->getAdoptID(), $adopt->getTradeStatus())));
                $searchTable->buildRow($cells);
            }
            $document->add($searchTable);
            return;
        }

        $searchForm = new FormBuilder("searchform", "adopt", "post");
        $searchForm->buildComment("Name: ", false)
                   ->buildTextField("name")
                   ->buildComment("Type: ", false)
                   ->buildTextField("type")
                   ->buildComment("Owner:", false)
                   ->buildTextField("owner")
                   ->buildComment("Gender:", false)
                   ->buildTextField("gender")
                   ->buildComment("MinLevel:", false)
                   ->buildTextField("minlevel")
                   ->buildButton("Search", "submit", "submit");
        $document->add($searchForm);
    }

    public function item()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;
        $document->setTitle($this->lang->item);

        if ($mysidia->input->post("submit")) {
            $ItemList = $this->getField("itemList");
            $iterator = $ItemList->iterator();
            $searchTable = new TableBuilder("searchresult");
            $searchTable->setAlign(new Align("center"));
            $searchTable->buildHeaders("ID", "Name", "Category", "Description", "Function", "Shop", "Price");
            $searchTable->setHelper(new SearchTableHelper());

            while ($iterator->hasNext()) {
                $item = $iterator->next();
                $cells = new LinkedList();
                $cells->add(new TCell($item->id));
                $cells->add(new TCell($item->itemname));
                $cells->add(new TCell($item->category));
                $cells->add(new TCell($item->description));
                $cells->add(new TCell($item->function));
                $cells->add(new TCell($searchTable->getHelper()->getShopLink($item->shop)));
                $cells->add(new TCell($item->price));
                $searchTable->buildRow($cells);
            }
            $document->add($searchTable);
            return;
        }

        $searchForm = new FormBuilder("searchform", "item", "post");
        $searchForm->buildComment("Itemname: ", false)
                   ->buildTextField("name")
                   ->buildComment("Category: ", false)
                   ->buildTextField("category");

        $funcList = $this->getField("funcList");
        $functions = new DropdownList("function");
        $functions->add(new Option("None Selected", "none"));
        $functions->fill($funcList);
        $searchForm->add(new Comment("Function: ", false));
        $searchForm->add($functions);

        $searchForm->buildComment("Shop:", false)
                   ->buildTextField("shop")
                   ->buildComment("MaxPrice:", false)
                   ->buildTextField("maxprice")
                   ->buildButton("Search", "submit", "submit");
        $document->add($searchForm);
    }
}
