<?php

class TextCacheEditor implements ICacheEditor {

    private $category;
    private $cachepath;

    public function SetValue($key, $value, $seconds = 0, $withversion = FALSE) {
        global $_cachedData;
    }

    public function Save() {
        global $_cachedData;
        if (!isset($_cachedData[$this->category])) {
            //nothing to save
            return;
        }
        $path = $this->cachepath . "/" . $this->category . "/";
        $cd = &$_cachedData[$this->category];

        if (isset($cd["keys"])) {
            foreach ($cd["keys"] as $key => &$value) {
                if (!isset($value["value"]))
                    continue;
                $fp = @fopen($path . $key, "w");
                if (!$fp)
                    continue;
                fwrite($fp, $value["version"] . "\n");
                fwrite($fp, $value["expire"] . "\n");
                fwrite($fp, gettype($value["value"]) . "\n");
                if (is_array($value["value"])
                        || is_object($value["value"]))
                    fwrite($fp, serialize($value["value"]));
                fclose($fp);
            }
        }
    }

    public function Remove($key) {
        
    }

    public function RemoveAll() {
        
    }

}
