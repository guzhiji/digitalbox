<?php

/**
 * 
 * @version 0.1.201202
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @license MIT License
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.cache.memcache
 */
class MemCacheReader implements ICacheReader {

    private $memcache;
    private $datalist;

    /**
     * name the function for refreshing the cache file;
     * null if the function is not set
     * @var callback
     */
    private $function = NULL;

    function __construct($host, $port) {
        $this->memcache = memcache_connect($host, $port);
        $this->datalist = array();
    }

    function __destruct() {
        memcache_close($this->memcache);
    }

    public function GetKeys() {
        throw new Exception("not supported");
    }

    public function GetValue($key, $version = 0, $randomfactor = 1) {
        $blocked = TRUE; //block reloading data, use old data instead
        if ($randomfactor == 1 ||
                ($randomfactor > 0 &&
                mt_rand(1, $randomfactor) == 1)) {
            $blocked = FALSE;
        }
        if (isset($this->datalist[$key])) {
            $data = $this->datalist[$key];
            if ($blocked || $version < 1 || $version <= $data[0]) {
                return $data[1];
            }
        }
        $data = memcache_get($this->memcache, $key);
        if (is_array($data)) {
            if ($blocked || $version < 1 || $version <= $data[0]) {
                $this->datalist[$key] = $data;
                return $data[1];
            }
        }
        if (!empty($this->function)) {
            call_user_func($this->function);
            $data = memcache_get($this->memcache, $key);
            if (is_array($data)) {
                $this->datalist[$key] = $data;
                return $data[1];
            }
        }
        return NULL;
    }

    public function SetRefreshFunction($function) {
        $this->function = $function;
    }

}
