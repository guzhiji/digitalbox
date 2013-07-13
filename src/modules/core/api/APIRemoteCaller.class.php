<?php

//TODO previliged caller
//TODO file upload
//TODO interanl interaction, auth with hashed and original ramdom code, login with session id to keep state
class APIRemoteCaller extends APICaller {

    private $url;
    public $timeout = 30;

    function __construct($api_url) {
        $this->url = $api_url;
    }

    protected function invoke($fname, array $funcparams) {

        $query = array();

        // function variable
        if (defined(IBC1_PREFIX . '_API_R_FUNC_NAME')) {
            $fvar = constant(IBC1_PREFIX . '_API_R_FUNC_NAME');
        } else {
            $fvar = 'f'; // default: f
        }

        // parameters 
        if (defined(IBC1_PREFIX . '_API_R_PARAM_SRC') &&
                constant(IBC1_PREFIX . '_API_R_PARAM_SRC') == 'GET') {
            $psrc = CURLOPT_HTTPGET;
            $query = $funcparams;
        } else {
            $psrc = CURLOPT_POST; // default: POST
        }

        // function name 
        if (defined(IBC1_PREFIX . '_API_R_FUNC_SRC') &&
                constant(IBC1_PREFIX . '_API_R_FUNC_SRC') == 'POST') {
            $fsrc = CURLOPT_POST;
            $funcparams[$fvar] = $fname;
        } else {
            $fsrc = CURLOPT_HTTPGET; // default: GET
            $query[$fvar] = $fname;
        }

        // request
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($c, CURLOPT_TIMEOUT, $this->timeout);
        if ($psrc == CURLOPT_POST || $fsrc == CURLOPT_POST) {
            curl_setopt($c, CURLOPT_POST, 1);
            curl_setopt($c, CURLOPT_POSTFIELDS, $funcparams);
        }
        curl_setopt($c, CURLOPT_URL, $this->url . (empty($query) ? '' : ('?' . queryString($query))));
        $r = curl_exec($c);
        curl_close($c);

        return $r;
    }

    public function call($fname, array $funcparams) {
        $r = $this->invoke($fname, $funcparams);
        return $this->read($r);
    }

}
