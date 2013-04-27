<?php

/* ------------------------------------------------------------------
 * This program is a prototype version in the InterBox project,
 *  using the MIT License.
 * http://code.google.com/p/interbox/
 * ------------------------------------------------------------------
 */

/**
 * a cache file reader implemented with php array 
 * and serialization features
 * 
 * @version 0.8.20120220
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.cache.phpcache
 */
class PHPCacheReader {

    /**
     * category name
     * @see PHPCacheEditor::$category
     * @var string
     */
    private $category;

    /**
     * the path to the directory where cache files are stored
     * @var string
     */
    private $path;

    /**
     * name the function for refreshing the cache file;
     * null if the function is not set
     * @var string
     */
    private $function = NULL;

    function __construct($cachepath, $categoryname) {
        GLOBAL $_cachedData;
        $this->category = $categoryname;
        $this->path = $cachepath;
        $cachefile = FormatPath($cachepath, "{$categoryname}.php");
        // while (safe_flocked($cachefile))
        //     usleep(200000);
        if (is_file($cachefile))
            require($cachefile);
        if (!isset($_cachedData[$categoryname]))
            $_cachedData[$categoryname] = array();
    }

    public function SetRefreshFunction($function) {
        $this->function = $function;
    }

    private function GetRefreshedValue($key) {
        if ($this->function != NULL) {

//            //refresh
//            //eval($this->function);
//            call_user_func($this->function);
//
//            //reload
//            $r = new PHPCacheReader($this->path, $this->category);
//            return $r->GetValue($key);

            return call_user_func($this->function);
        }
        return NULL;
    }

    public function GetValue($key, $version = 0, $randomfactor = 1) {

        GLOBAL $_cachedData;

        //check category
        if (!isset($_cachedData[$this->category]))
            return NULL;
        $cd = &$_cachedData[$this->category];

        //$process random factor
        $notblocked = FALSE;
        if ($randomfactor > 0 //==0 --> don't refresh it
                && ($randomfactor == 1 //==1 --> do refresh it
                || mt_rand(1, $randomfactor) == 1) //-->do random
        ) {
            $notblocked = TRUE;
        }

        //check expiry time for the category
        if ($notblocked && isset($cd["expire"]) && $cd["expire"] < time()) {
            return $this->GetRefreshedValue($key);
        }

        //check key
        if (!isset($cd["keys"]))
            return NULL;
        if (!isset($cd["keys"][$key]))
            return NULL;
        $cd = &$cd["keys"][$key];

        //force to refresh
        if ($notblocked &&
                isset($cd["version"]) &&
                $version > 0 &&
                $version > $cd["version"]) {
            return $this->GetRefreshedValue($key);
        }

        //check expiry time for the key
        if ($notblocked && isset($cd["expire"]) && $cd["expire"] < time()) {
            return $this->GetRefreshedValue($key);
        }

        //check value
        if (isset($cd["value"])) {
            if (isset($cd["serialized"]) && is_string($cd["value"])) {
                return unserialize($cd["value"]);
            } else {
                return $cd["value"];
            }
        }
        return NULL;
    }

    public function GetKeys() {

        GLOBAL $_cachedData;

        //check category
        if (!isset($_cachedData[$this->category]))
            return array();
        $cd = &$_cachedData[$this->category];

        //check expiry time for the category
        if (isset($cd["expire"]) && $cd["expire"] < time()) {
            return array();
        }

        //check key
        if (!isset($cd["keys"]))
            return array();

        //read all keys
        $keys = array();
        foreach ($cd["keys"] as $key => $value) {
            //check expiry time for the key
            if (isset($value["expire"]) && $value["expire"] < time())
                continue;
            $keys[] = $key;
        }
        return $keys;
    }

}
