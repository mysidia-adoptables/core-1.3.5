<?php

class VisitorCreator extends UserCreator
{
    protected $identitiy;
    protected $user;

    public function __construct(protected $ip)
    {
        $this->identity = $this->getidentity();
    }

    public function create()
    {
        $user = match ($this->identity) {
            "Spider" => $this->create_spider(),
            default => $this->create_guest(),
        };
        return $user;
    }

    public function massproduce()
    {
        return false;
    }

    private function create_spider()
    {
        return new Spider($this->ip);
    }

    private function create_guest()
    {
        return new Visitor($this->ip);
    }

    private function create_banned()
    {
        return new Spider($this->ip);
    }
}
