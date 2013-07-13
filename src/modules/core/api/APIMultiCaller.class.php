<?php

class APIMultiCaller extends APICaller {

    private $urls = array();

    public function newServer($url) {
        $this->urls[] = $url;
        return $this;
    }

    public function invoke($fname, array $params) {
        $mh = curl_multi_init();
        $h = array();
        foreach ($this->urls as $url) {
            $c = curl_init();
            $url.= '?f=' . urlencode($fname);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($c, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($c, CURLOPT_POST, 1);
            curl_setopt($c, CURLOPT_POSTFIELDS, $params);
            curl_setopt($c, CURLOPT_URL, $url);
            $h[] = $c;
            curl_multi_add_handle($mh, $c);
        }

        $active = NULL;
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($mh) != -1) {
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

        $r = array();
        foreach ($h as $c) {
            $r[] = $this->read(curl_multi_getcontent($c)); //TODO may be more than 1 error
            curl_multi_remove_handle($mh, $c);
        }
        curl_multi_close($mh);
        foreach ($h as $c) {
            curl_close($c);
        }
        return $r;
    }

    public function call($fname, array $funcparams) {
        return $this->invoke($fname, $funcparams);
    }

}

//examples:
//http://php.net/manual/en/function.curl-multi-exec.php
// create both cURL resources
$ch1 = curl_init();
$ch2 = curl_init();

// set URL and other appropriate options
curl_setopt($ch1, CURLOPT_URL, "http://lxr.php.net/");
curl_setopt($ch1, CURLOPT_HEADER, 0);
curl_setopt($ch2, CURLOPT_URL, "http://www.php.net/");
curl_setopt($ch2, CURLOPT_HEADER, 0);

//create the multiple cURL handle
$mh = curl_multi_init();

//add the two handles
curl_multi_add_handle($mh, $ch1);
curl_multi_add_handle($mh, $ch2);

$active = null;
//execute the handles
do {
    $mrc = curl_multi_exec($mh, $active);
} while ($mrc == CURLM_CALL_MULTI_PERFORM);

while ($active && $mrc == CURLM_OK) {
    if (curl_multi_select($mh) != -1) {
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    }
}

//close the handles
curl_multi_remove_handle($mh, $ch1);
curl_multi_remove_handle($mh, $ch2);
curl_multi_close($mh);
/*
 * http://php.net/manual/en/function.curl-multi-info-read.php
 */
$urls = array(
    "http://www.cnn.com/",
    "http://www.bbc.co.uk/",
    "http://www.yahoo.com/"
);

$mh = curl_multi_init();

foreach ($urls as $i => $url) {
    $conn[$i] = curl_init($url);
    curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, 1);
    curl_multi_add_handle($mh, $conn[$i]);
}

do {
    $status = curl_multi_exec($mh, $active);
    $info = curl_multi_info_read($mh);
    if (false !== $info) {
        var_dump($info);
    }
} while ($status === CURLM_CALL_MULTI_PERFORM || $active);

foreach ($urls as $i => $url) {
    $res[$i] = curl_multi_getcontent($conn[$i]);
    curl_close($conn[$i]);
}

var_dump(curl_multi_info_read($mh));

/*
 * http://php.net/manual/zh/function.curl-multi-remove-handle.php
 */
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, 'http://www.example.com/');
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);

$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, 'http://www.example.net/');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

$mh = curl_multi_init();

curl_multi_add_handle($mh, $ch1);
curl_multi_add_handle($mh, $ch2);

$active = null;

do {
    curl_multi_exec($mh, $active);
} while ($active);

$res1 = curl_multi_getcontent($ch1);
$res2 = curl_multi_getcontent($ch2);

curl_multi_remove_handle($mh, $ch1);
curl_multi_remove_handle($mh, $ch2);

curl_multi_close($mh);

curl_close($ch1);
curl_close($ch2);

