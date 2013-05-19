<?php

class TextCacheReader {
    
    private $cachepath;

    private $data = array();

    function __construct($cachepath, $category) {

        $this->cachepath = FormatPath($cachepath, $category);

    }

    public function SetRefreshFunction($function) {

        $this->function = $function;

    }

    private function GetRefreshedValue($key) {

        if (!empty($this->function))
            return call_user_func($this->function, $key);
        
        return NULL;

    }

    private function GetCacheFile($keypath) {

        if (is_file($keypath.'.txt'))
            return array(
                'type' => 'plain',
                'name' => $keypath.'.txt'
            );

        if (is_file($keypath.'.serialized'))
            return array(
                'type' => 'serialized',
                'name' => $keypath.'.serialized'
            );

        return NULL;

    }

    private function CheckExpiration($keypath) {

        if (is_file($keypath.'.expire'))
            return time() > intval(file_get_contents($keypath.'.expire'));

        return FALSE;
    }

    private function CheckUpdate($keypath, $version) {

        if (is_file($keypath.'.version'))
            return $version > fileatime($keypath.'.version');

        return FALSE;

    }

    public function GetValue($key, $version = 0) {

        if (isset($this->data[$key]))
            return $this->data[$key];

        // lock the category
        $lock = @fopen(FormatPath($this->cachepath, '.lock'), 'w');
        if (!$lock)
            throw new Exception('cannot lock the category');
        if (!flock($lock, LOCK_EX | LOCK_NB))
            throw new Exception('cannot lock the category');

        $keypath = FormatPath($this->cachepath, $key);

        $cachefile = $this->GetCacheFile($keypath);

        if (
            empty($cachefile) ||
            $this->CheckExpiration($keypath) || 
            $this->CheckUpdate($keypath, $version)
        ) {

            $value = $this->GetRefreshedValue($key);

        } else {

            $value = file_get_contents($cachefile['name']);
            if ($cachefile['type'] == 'serialized')
                $value = unserialize($value);

        }

        // unlock the category
        flock($lock, LOCK_UN);
        fclose($lock);

        $this->data[$key] = $value;
        return $value;

    }
}
