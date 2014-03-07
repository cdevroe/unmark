<?php defined("BASEPATH") or exit("No direct script access allowed");

class Plain_Cache {

    private $cache      = '';
    private $has_custom = false;

    public function __construct()
    {
        $this->cache = new stdClass;
        $file = CUSTOMPATH . '/libraries/Cache.php';
        if (file_exists($file)) {
            include_once $file;
            $this->cache = new Cache;
        }
    }

    public function __call($method, $args)
    {
        print $method . "<BR>\n";
        print_r($args) . "<BR>\n";
        if (method_exists($this->cache, $method)) {
            $this->cache->$method($args);
        }
    }

}