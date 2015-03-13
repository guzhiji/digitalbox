<?php

//TODO move these constants to a common lib
define('IBC1_API_E_OK', 0);
define('IBC1_API_E_NOTFOUND_CONTENT', 1);
define('IBC1_API_E_NOTFOUND_FUNCTION', 2);
define('IBC1_API_E_MISSING_PARAMETER', 3);
define('IBC1_API_E_FAILURE', 4);

class APICaller {

    public $error_code = -1; //not called
    public $error_msg = '';

    protected function read($r) {
        if ($r !== FALSE) {
            $a = json_decode($r);
            if (isset($a['code'])) {
                if ($a['code'] == IBC1_API_E_OK) {
                    $this->error_code = IBC1_API_E_OK;
                    $this->error_msg = '';
                    if (isset($a['data']))
                        return $a['data'];
                }else {
                    $this->error_code = $a['code'];
                    $this->error_msg = isset($a['msg']) ? $a['msg'] : 'unknown error';
                }
            } else {
                $this->error_code = IBC1_API_E_FAILURE;
                $this->error_msg = 'invalid response';
            }
        } else {
            $this->error_code = IBC1_API_E_FAILURE;
            $this->error_msg = 'curl error';
        }
        return NULL;
    }

    public function invoke($fname, array $funcparams) {

        $functions = &$GLOBALS[IBC1_PREFIX . '_API_FUNCTIONS'];

        if (empty($fname) || !isset($functions[$fname])) {
            return array(
                'code' => IBC1_API_E_NOTFOUND_FUNCTION,
                'msg' => 'function not found'
            );
        }

        $params_p = $functions[$fname];
        $params_v = array();
        foreach ($params_p as $pk => $pp) {
            if (isset($funcparams[$pk])) {
                $v = $funcparams[$pk];
                if (!empty($pp) && isset($pp['filter']))
                    $v = call_user_func($pp['filter'], $v);
            } else {
                if (!empty($pp) || !isset($pp['optional']) || !$pp['optional']) {
                    return array(
                        'code' => IBC1_API_E_MISSING_PARAMETER,
                        'msg' => 'missing parameter(s)'
                    );
                }
                $v = isset($pp['default']) ? $pp['default'] : NULL;
            }
            $params_v[] = $v;
        }

        $r = call_user_func_array($fname, $params_v);
        if ($r === NULL)
            return array(
                'code' => IBC1_API_E_NOTFOUND_CONTENT,
                'msg' => 'content not found'
            );
        else
            return array(
                'code' => IBC1_API_E_OK,
                'data' => $r
            );
    }

    public function call($fname, array $funcparams) {
        $r = $this->invoke($fname, $funcparams);
        return $this->read($r);
    }

}