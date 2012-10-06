<?php

/**
 * 
 * @version 0.1.20120225
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @license MIT License
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.cache.memcache
 */
class MemCacheEditor implements ICacheEditor {

    private $memcache;
    private $changelist;
    private $removallist;
    private $toremovall;

    function __construct($host, $port) {
        $this->memcache = memcache_connect($host, $port);
        $this->changelist = array();
        $this->removallist = array();
        $this->toremovall = FALSE;
    }

    function __destruct() {
        memcache_close($this->memcache);
    }

    public function SetValue($key, $value, $seconds = 0, $withversion = FALSE) {
        $version = 0;
        if ($withversion)
            $version = time();
        $data = array($version, $value);
        $this->changelist[$key] = array($data, $seconds);
    }

    public function Remove($key) {
        if (isset($this->changelist[$key])) {
            unset($this->changelist[$key]);
        }
        $this->removallist[] = $key;
    }

    public function RemoveAll() {
        $this->toremovall = TRUE;
    }

    public function Save() {
        if ($this->toremovall) {
            memcache_flush();
        } else {
            foreach ($this->changelist as $key => $data) {
                if ($data[1] == 0)//remain expiry time
                    memcache_set($this->memcache, $key, $data[0], 0);
                else if ($data[1] < 0)//remove expiry time
                    memcache_set($this->memcache, $key, $data[0], 0, 0);
                else//update expiry time
                    memcache_set($this->memcache, $key, $data[0], 0, $data[1]);
            }
            foreach ($this->removallist as $key) {
                memcache_delete($this->memcache, $key);
            }
        }
    }

}
