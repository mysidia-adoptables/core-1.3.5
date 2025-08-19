<?php

class AdminCPView extends View
{
    public const WELCOME = "Welcome to your Admin CP";
    public const LOGIN = "Please enter your username and password to log into admin control panel.<br><br>";
    public const SUCCESS = "Login Successful, you are being redictred to the index page.";

    #[\Override]
    public function index()
    {
        $mysidia = Registry::get("mysidia");
        $document = $this->document;
        $document->setTitle(self::WELCOME);
        $status = $mysidia->session->fetch("status");
        if ($status) {
            $this->login($status);
            return;
        }
        $document->addLangvar($this->lang->default.$this->lang->credits);
    }

    private function login($status)
    {
        $method = "{$status}Login";
        $this->$method();
    }

    private function prepareLogin()
    {
        $document = $this->document;
        $document->addLangvar(self::LOGIN);

        $loginForm = new FormBuilder("loginform", "", "post");
        $loginForm->buildComment("username: ", false)
                  ->buildTextField("username")
                  ->buildComment("password: ", false)
                  ->buildPasswordField("password", "password", "", true)
                  ->buildButton("Log In", "submit", "submit");
        $document->add($loginForm);
    }

    private function handleLogin()
    {
        $document = $this->document;
        $document->addLangvar(self::SUCCESS);
        $this->refresh(3);
    }
}
