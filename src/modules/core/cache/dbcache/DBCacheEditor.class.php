<?php

/**
 * cache_key
 * cache_value
 * cache_serialized
 * cache_expire
 * cache_version
 * 
 * @version 0.1.20120225
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @license MIT License
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.cache.dbcache
 */
class DBCacheEditor implements ICacheEditor {

    /**
     * take a data table as a group
     * array(
     *      "extension"=>[],
     *      "host"=>[],
     *      "port"=>[],
     *      "username"=>[],
     *      "password"=>[],
     *      "database"=>[],
     *      "table"=>[]
     * )
     * @var array 
     */
    private $dbinfo;
    private $datalist;
    private $removallist;
    private $toremovall;

    function __construct($dbinfo) {
        $this->dbinfo = $dbinfo;
        $this->datalist = array();
        $this->removallist = array();
        $this->toremovall = FALSE;
    }

    public function Remove($key) {
        if (isset($this->datalist[$key]))
            unset($this->datalist[$key]);
        $this->toremovall[] = $key;
    }

    public function RemoveAll() {
        $this->toremovall = TRUE;
    }

    public function Save() {
        
    }

    public function SetValue($key, $value, $seconds = 0, $withversion = FALSE) {
        $version = 0;
        if ($withversion)
            $version = time();
        $this->datalist[$key] = array($value, $seconds, $version);
    }

}
