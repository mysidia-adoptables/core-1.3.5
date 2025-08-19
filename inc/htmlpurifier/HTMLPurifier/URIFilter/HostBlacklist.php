<?php

class HTMLPurifier_URIFilter_HostBlacklist extends HTMLPurifier_URIFilter
{
    public $name = 'HostBlacklist';
    protected $blacklist = [];
    #[\Override]
    public function prepare($config)
    {
        $this->blacklist = $config->get('URI.HostBlacklist');
        return true;
    }
    public function filter(&$uri, $config, $context)
    {
        foreach ($this->blacklist as $blacklisted_host_fragment) {
            if (str_contains((string) $uri->host, (string) $blacklisted_host_fragment)) {
                return false;
            }
        }
        return true;
    }
}

// vim: et sw=4 sts=4
