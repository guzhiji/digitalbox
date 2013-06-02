<?php

class TextCacheEditor {
    
    private $cachepath;

    private $data = array();

    private $rmAll = FALSE;

    private $rmList = array();

    function __construct($cachepath, $category) {

        $this->cachepath = FormatPath($cachepath, $category);

    }

    public function SetValue($key, $value, $seconds = 0, $withversion = FALSE) {

        // check key
        if (!preg_match("/^[^\r\n\"\']+$/i", $key)) {
            throw new Exception("invalid key");
        }

        // store in memory
        $this->data[$key] = array(
            'value' => $value,
            'seconds' => $seconds,
            'withversion' => $withversion
        );

    }

    public function Remove($key) {

        // remote file for the key
        $this->rmList[] = $key;

    }

    public function RemoveAll() {

        // remove the whole directory
        $this->rmAll = TRUE;

    }

    public function Save() {

        if (!is_dir($this->cachepath))
            mkdir($this->cachepath);

        // remove
        if ($this->rmAll) {

            // delete all files in cache path
            foreach (scandir($this->cachepath) as $file) {

                $filepath = FormatPath($this->cachepath, $file);
                if (is_file($filepath)) unlink($filepath);

            }
            rmdir($this->cachepath);

        } else {

            // delete keys that need to be removed
            foreach ($this->rmList as $key) {

                $cachefile = FormatPath($this->cachepath, $key);

                if (is_file($cachefile.'.txt'))
                    unlink($cachefile.'.txt');

                if (is_file($cachefile.'.serialized'))
                    unlink($cachefile.'.serialized');

                if (is_file($cachefile.'.expire'))
                    unlink($cachefile.'.expire');

                if (is_file($cachefile.'.version'))
                    unlink($cachefile.'.version');       

            }

        }

        // store key-value pairs in files
        foreach ($this->data as $key => $data) {

            $cachefile = FormatPath($this->cachepath, $key);

            $cachedata = $data['value'];
            if (!is_string($cachedata))
                $cachedata = file_put_contents(
                    $cachefile.'.serialized',
                    serialize($cachedata)
                );
            else
                file_put_contents($cachefile.'.txt', $cachedata);

            if ($data['seconds'] > 0)
                file_put_contents(
                    $cachefile.'.expire',
                    strval(time() + $data['seconds'])
                );

            if ($data['withversion'])
                touch($cachefile.'.version'); // version is the access time

        }

    }

}