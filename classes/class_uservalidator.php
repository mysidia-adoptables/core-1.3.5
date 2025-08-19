<?php

class UserValidator extends Validator
{
    private $usergroup;

    public function __construct(private readonly User $user, $value = [], $action = "")
    {
        // Fetch the basic properties for usergroup

        $this->initialize($action, $value);
        $this->usergroup = $this->user->usergroup;
        // Successfully instantiate the user validator object
    }

    public function validate($action = "", $data = "")
    {
        // The core method validate, it acts like a controller and sends requests to different private methods based on the action
        $action = (empty($action)) ? $this->action : $action;
        if (empty($action)) {
            throw new Exception('The validation action is empty, something must be seriously wrong...');
        }

        // Now we are validating our user data or input!
        $validarray = ["uid", "username", "password", "email", "ip", "tos", "answer", "money", "permissions", "friends"];
        $method = "{$action}validate";

        if (in_array($action, $validarray)) {
            return $this->$method($data);
        } else {
            throw new Exception('Validation action invalid...');
        }
    }

    protected function uidvalidate($uid = "")
    {
        // The uid validator, it merely checks if the uid exists in database or not
        $uid = (empty($uid)) ? $this->value['uid'] : $uid;
        $this->emptyvalidate($uid);
        return $this->datavalidate("users", ["uid"], "uid = '{$uid}'");
    }

    protected function usernamevalidate($username = "")
    {
        // The username validator, note its a bit complicate here due to the different mechanism of username check for register and other validators
        $username = (empty($username)) ? $this->value['username'] : $username;
        $this->emptyvalidate($username);
        $userexist = $this->datavalidate("users", ["username"], "username = '{$username}'");
        return $userexist;
    }

    protected function passwordvalidate($password = "")
    {
        $this->emptyvalidate($this->value['password']);
        if ($this->datavalidate("users", ["username", "password", "salt"], "username = '{$this->value['username']}'")) {
            $encryptpassword = passencr($this->value['username'], $this->value['password'], $this->data->salt);
            if ($this->data->username == $this->value['username'] and $this->data->password == $encryptpassword) {
                return true;
            } else {
                $this->seterror("Incorrect Password for user {$this->data->username}.");
                return false;
            }
        } else {
            $this->seterror("The user does not appear in the site database...");
            return false;
        }
    }

    protected function emailvalidate($email = "")
    {
        $email = (empty($email)) ? $this->value['email'] : $email;
        $this->emptyvalidate($email);
        $regex = '/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i';
        if (!$this->matchvalidate($regex, $email, "preg_match")) {
            $this->seterror("The email is invalid...");
            return false;
        } else {
            return true;
        }
    }

    protected function ipvalidate($ip = "")
    {
        $ip = (empty($ip)) ? $this->value['ip'] : $ip;
        if ($ip != $_SERVER['REMOTE_ADDR']) {
            $this->seterror("The ip validation fails...");
            return false;
        } else {
            return true;
        }
    }

    protected function datevalidate($date = "")
    {
        if (!$this->emptyvalidate($this->value['date'])) {
            $this->seterror("The value for date field is invalid, please go back and revise it.");
            return false;
        } else {
            return true;
        }
    }

    protected function moneyvalidate($amount = "")
    {
        $amount = (empty($amount)) ? $this->value['amount'] : $amount;
        $this->numericvalidate($amount);
        if ($this->user->money < abs($amount)) {
            $this->seterror("Cannot afford this transaction...");
            return false;
        } else {
            return true;
        }
    }

    protected function permissionvalidate($permission = "")
    {
        $permission = (empty($permission)) ? $this->value['permission'] : $permission;
        $status = $this->user->getstatus();
        if ($status->$permission == 'no') {
            $this->seterror("Accessed denied, the user does not have permission");
            return false;
        } else {
            return true;
        }
    }

    protected function friendsvalidate($permission = "")
    {
        // Coming soon!
    }
}
