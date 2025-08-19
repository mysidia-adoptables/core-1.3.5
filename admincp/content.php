<?php

class ACPContentController extends AppController
{
    public const PARAM = "pageurl";
    private $editor;

    public function __construct()
    {
        parent::__construct();
        include_once("../inc/ckeditor/ckeditor.php");
        $mysidia = Registry::get("mysidia");
        $this->editor = new CKEditor();
        $this->editor->basePath = '../../inc/ckeditor/';
        if ($mysidia->usergroup->getpermission("canmanagecontent") != "yes") {
            throw new NoPermissionException("You do not have permission to manage users.");
        }
    }

    public function index()
    {
        parent::index();
        $mysidia = Registry::get("mysidia");
        $total = $mysidia->db->select("content")->rowCount();
        if ($total == 0) {
            throw new InvalidIDException("default_none");
        }
        $pagination = new Pagination($total, $mysidia->settings->pagination, "admincp/content");
        $pagination->setPage($mysidia->input->get("page"));
        $stmt = $mysidia->db->select("content", [], "1 LIMIT {$pagination->getLimit()},{$pagination->getRowsperPage()}");
        $this->setField("pagination", $pagination);
        $this->setField("stmt", new DatabaseStatement($stmt));
    }

    public function add()
    {
        $mysidia = Registry::get("mysidia");
        if ($mysidia->input->post("submit")) {
            if (!$mysidia->input->post("pageurl")) {
                throw new BlankFieldException("url");
            }
            if (!$mysidia->input->post("pagetitle")) {
                throw new BlankFieldException("title");
            }
            if (!$mysidia->input->post("pagecontent")) {
                throw new BlankFieldException("content");
            }
            $date = new DateTime();
            $content = $this->format($mysidia->input->post("pagecontent"));
            $group = ($mysidia->input->post("group") == "none") ? "" : $mysidia->input->post("group");
            $mysidia->db->insert("content", ["cid" => null, "page" => $mysidia->input->post("pageurl"), "title" => $mysidia->input->post("pagetitle"), "date" => $date->format('Y-m-d'), "content" => $content,
                                                  "level" => null, "code" => $mysidia->input->post("promocode"), "item" => $mysidia->input->post("item"), "time" => $mysidia->input->post("time"), "group" => $group]);
            return;
        }
        $editor = $this->editor->editor("pagecontent", "CKEditor for Mys v1.3.4");
        $this->setField("editor", new DataMysObject($editor));
    }

    public function edit()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("pageurl")) {
            // A page has yet been selected, return to the index page.
            $this->index();
            return;
        }
        $content = $mysidia->db->select("content", [], "page = '{$mysidia->input->get("pageurl")}'")->fetchObject();

        if (!is_object($content)) {
            throw new InvalidIDException("nonexist");
        } elseif ($mysidia->input->post("submit")) {
            if (!$mysidia->input->post("pagetitle")) {
                throw new BlankFieldException("title");
            }
            if (!$mysidia->input->post("pagecontent")) {
                throw new BlankFieldException("content");
            }
            $content = $this->format($mysidia->input->post("pagecontent"));
            $stmt = $mysidia->db->select("content", [], "page='{$mysidia->input->get("pageurl")}'");
            if ($page = $stmt->fetchObject()) {
                $group = ($mysidia->input->post("group") == "none") ? "" : $mysidia->input->post("group");
                $mysidia->db->update("content", ["content" => $content, "title" => $mysidia->input->post("pagetitle"), "code" => $mysidia->input->post("promocode"), "item" => $mysidia->input->post("item"),
                                                      "time" => $mysidia->input->post("time"), "group" => $group], "page='{$mysidia->input->get("pageurl")}'");
                return;
            } else {
                throw new InvalidIDException("nonexist");
            }
        } else {
            $this->editor->basePath = '../../../inc/ckeditor/';
            $editor = $this->editor->editor("pagecontent", $this->format($content->content));
            $this->setField("editor", new DataMysObject($editor));
            $this->setField("content", new DataMysObject($content));
        }
    }

    public function delete()
    {
        $mysidia = Registry::get("mysidia");
        if (!$mysidia->input->get("pageurl")) {
            // A user has yet been selected, return to the index page.
            $this->index();
            return;
        }
        if ($mysidia->input->get("pageurl") == "index" or $mysidia->input->get("pageurl") == "tos") {
            throw new InvalidIDException("special");
        }
        $mysidia->db->delete("content", "page='{$mysidia->input->get("pageurl")}'");
    }

    private function format($text)
    {
        $text = html_entity_decode((string) $text);
        $text = str_replace("\r\n", "", $text);
        $text = stripslashes($text);
        return $text;
    }
}
