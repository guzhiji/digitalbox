<?php

/**
 * 
 * @version 0.1.20120225
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @license MIT License
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.cache.dbcache
 */
class DBCacheReader implements ICacheReader {

    private $dbinfo;
    private $datalist;
    private $function = NULL;

    function __construct($dbinfo) {
        $this->dbinfo = $dbinfo;
        $this->datalist = array();
        //TODO load all key/value pairs and other attached info in the table
    }

    public function GetKeys() {
        
    }

    public function GetValue($key, $version = 0, $randomfactor = 1) {
        
    }

    public function SetRefreshFunction($function) {
        $this->function = $function;
    }

}