<?php defined("BASEPATH") or exit("No direct script access allowed");

class Cache {

    public $extension          = '.cache';
    public $ttl                = 86400;        // 1 Day
    public $use_apc            = true;

    protected $apc_enabled     = false;
    protected $cache_directory = null;
    protected $errors          = array();
    protected $timer           = 0;

    function __construct($ttl=86400, $extension='.cache', $use_apc=true)
    {
        $this->timer = $this->timer();
        $this->apcInstalled();

        // Set cache directory
        $this->cache_directory = $_SERVER['DOCUMENT_ROOT'] . '/_cache/app-cache/';

        // Defaults
        $this->ttl       = (! empty($ttl) && is_numeric($ttl) && $ttl > 0) ? $ttl : $this->ttl;
        $this->extension = (! empty($extension)) ? $extension : $this->extension;
        $this->use_apc   = (! empty($use_apc)) ? true : false;
    }

    public function add($k, $v, $override=false)
    {
        if (! empty($k) && ! stristr($k, '-notfound-')) {
            if ($this->useAPC() === true) {
                if ($override === true || $this->expired($k) === true) {
                    apc_delete($k);
                    apc_store($k, $v, $this->ttl);
                }
            }
            elseif ($this->checkDir() === true) {
                if ($o === true || $this->expired($k)) {
                    @file_put_contents($this->cache_directory . $k . $this->extension, $v);
                }
            }
        }
    }

    protected function apcInstalled()
    {
        $functions = get_defined_functions();
        if (in_array('apc_store', $functions['internal'])) {
            $this->apc_enabled = true;
        }
    }

    protected function checkDir()
    {
        $this->cache_directory .= (!empty($this->cache_directory) && substr($this->cache_directory, strlen($this->cache_directory)-1, 1) != '/') ? '/' : null;
        if (empty($this->cache_directory)) {
            array_push($this->errors, 'You must set a cache directory and set the proper permissions before you can begin to cache any files.');
            return false;
        }

        if (! is_dir($this->cache_directory)) {
            array_push($this->errors, 'The cache directory submitted is not a valid or existing directory.');
            return false;
        }

        if (! is_writable($this->cache_directory)) {
            array_push($this->errors, 'The cache directory is not writable.');
            return false;
        }

        return true;
    }

    public function clear()
    {
        if ($this->useAPC() === true) {
            apc_clear_cache('user');
            apc_clear_cache();
        }
        elseif ($this->checkDir() === true) {
            if ($dh = opendir($this->cache_directory)) {
                while (($file = readdir($dh)) !== false) {
                    if (stristr($file, $this->extension)) {
                        @unlink($this->cache_directory . $file);
                    }
                }
                closedir($dh);
            }
        }
    }

    public function delete($k)
    {
        if (! empty($k)) {
            if (substr($k, strlen($k)-1) == '*') {
                $this->deleteAll($k);
            }
            else {
                if ($this->useAPC() === true) {
                    apc_delete($k);
                }
                elseif ($this->checkDir() === true && file_exists($this->cache_directory . $k . $this->extension)) {
                    @unlink($this->cache_directory . $k . $this->extension);
                }
            }
        }
    }

    public function deleteAll($k)
    {
        if (! empty($k)) {
            $k = str_replace('*', '.*?', $k);
            $k = str_replace('-', '\-', $k);
            if ($this->useAPC() === true) {
                $cache = apc_cache_info('user');
                foreach ($cache['cache_list'] as $key => $arr) {
                    if (preg_match('/' . $k . '/', $arr['info'])) {
                        apc_delete($arr['info']);
                    }
                }
            }
            elseif ($this->checkDir() === true) {
                if ($handle = opendir($this->cache_directory)) {

                    while (false !== ($file = readdir($handle))) {
                        if (preg_match('/' . preg_quote($k) . '.*?/', $file)) {
                            @unlink($this->cache_directory . $file);
                        }
                    }

                    closedir($handle);
                }
            }
        }
    }

    public function expired($k)
    {
        if (! empty($k)) {
            if ($this->useAPC() === true) {
                return (! $c = apc_fetch($k)) ? true : false;
            }
            elseif ($this->checkDir() === true) {
                $u = @filemtime($this->cache_directory . $k . $this->extension);
                return ($u === false || (time() - $this->ttl) > $u) ? true : false;
            }
        }
        return true;
    }

    public function read($k)
    {
        if (! empty($k)) {
            if ($this->useAPC() === true) {
                return apc_fetch($k);
            }
            elseif ($this->checkDir() === true && file_exists($this->cache_directory . $k . $this->extension)) {
                return file_get_contents($this->cache_directory . $k . $this->extension);
            }
        }
        return false;
    }

    public function show_errors()
    {
        if (@count($this->errors) > 0) {
            print implode('<br />', $this->errors);
        }
    }

    public function timer()
    {
        list($msec, $sec) = explode(' ', microtime());
        return ((float)$msec + (float)$sec);
    }

    public function timerCalculate($use_timer=null, $decimals=5)
    {
        $timer = (empty($use_timer)) ? $this->timer : $use_timer;
        return number_format($this->timer() - $timer, $decimals, '.', '');
    }

    protected function useAPC()
    {
        return ($this->use_apc === false || $this->apc_enabled === false) ? false : true;
    }

}