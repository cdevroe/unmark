<?php defined("BASEPATH") or exit("No direct script access allowed");

class Plain_Cache {

    public function __call($method, $args)
    {
        $file = CUSTOMPATH . '/libraries/Cache.php';
        if (file_exists($file)) {
            include_once $file;
            $cache = new Cache;
            if (method_exists($cache, $method)) {
                if (! empty($args)) {
                    return call_user_func_array(array($cache, $method), $args);
                }
                else {
                    return $cache->$method();
                }
            }
            else {
                return false;
            }
        }
        return false;
    }

}