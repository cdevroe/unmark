<?php defined("BASEPATH") or exit("No direct script access allowed");

class Plain_Cache {

    public function __call($method, $args)
    {
        $file = CUSTOMPATH . '/libraries/CCache.php';
        if (file_exists($file)) {
            include_once $file;
            $cache = new CCache;
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