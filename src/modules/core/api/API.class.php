<?php

/**
 * 
 * <code>
 * //persist configuration file
 * (new API())
 *     ->newFunction('pw_dynasty_list', array())//no parameter
 *     ->newFunction('pw_poet_get', array(
 *                      'id' => NULL //1 parameter without validation
 *                  ))
 *     ->newFunction('pw_poet_list', array( //2 parameters
 *                      'pagenumber' => array(
 *                          'optional' => TRUE, 
 *                          'default' => 1, 
 *                          'filter' => 'intval'
 *                      ),
 *                      'dynastyid' => array(
 *                          'optional' => TRUE, 
 *                          'default' => 0
 *                      )
 *                  ))
 *     ...
 *     ->saveConfig('pw.conf.php');
 * </code> 
 * <code>
 * // pw.conf.php
 * $GLOBALS[IBC1_PREFIX . '_API_FUNCTIONS'] = array(
 *     'pw_dynasty_list' => array(),//no parameter
 *     'pw_poet_get' => array(
 *         'id' => NULL //1 parameter without validation
 *     ),
 *     'pw_poet_list' => array( //2 parameters
 *         'pagenumber' => array(
 *             'optional' => TRUE, 
 *             'default' => 1, 
 *             'filter' => 'intval'
 *         ),
 *         'dynastyid' => array(
 *             'optional' => TRUE, 
 *             'default' => 0
 *         )
 *     ), 
 *     ...
 * );
 * </code> 
 * <code>
 * //accept call from http
 * require 'pw.lib.php';
 * $api = new API();
 * $api->loadConfig('pw.conf.php')
 *     ->acceptCall();
 * </code> 
 * 
 * @version 0.2.20121226
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.api
 */
class API {

    /**
     * load a configuration file that provides information of APIs.
     * 
     * @param string $configfile
     * @return API 
     */
    public function loadConfig($configfile) {
        require $configfile;
        return $this;
    }

    /**
     * store information of APIs as a configuration file (php script).
     * 
     * @global $GLOBALS['IBC1_API_FUNCTIONS']
     * @param string $filename
     * @return API 
     */
    public function saveConfig($filename) {
        $data = "<?php\n\$GLOBALS[IBC1_PREFIX . '_API_FUNCTIONS']=";
        $data.= var_export($GLOBALS[IBC1_PREFIX . '_API_FUNCTIONS'], TRUE) . ';';
        file_put_contents($filename, $data);
        return $this;
    }

    /**
     * add configuration for a function.
     * 
     * @global $GLOBALS['IBC1_API_FUNCTIONS']
     * @param string $fname
     * @param array $paramproperties
     * <code>
     * array(
     *      '[parameter name]' => NULL or array(
     *          'optional' => [TRUE or FALSE],
     *          'default'  => [default value],
     *          'filter'   => '[name of filter function]'
     *      )// [, more parameters ...]
     * )
     * </code>
     * @return API 
     */
    public function newFunction($fname, array $paramproperties) {
        $GLOBALS[IBC1_PREFIX . '_API_FUNCTIONS'][$fname] = $paramproperties;
        return $this;
    }

    /**
     * accept a remote call to a configured API and return its result in JSON format.
     * 
     * function variable
     * - define(IBC1_PREFIX . '_API_R_FUNC_NAME','myfunc');
     * - default: 'f'.
     * function name source
     * - define(IBC1_PREFIX . '_API_R_FUNC_SRC','GET');
     * - default: HTTP GET method.
     * parameter source
     * - define(IBC1_PREFIX . '_API_R_PARAM_SRC','GET');
     * - default: HTTP POST method.
     * note that this method automatically exists execution after data sent
     */
    public function acceptCall() {

        header('Content-Type: text/plain');
        //no client-side caching
        header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.

        if (defined(IBC1_PREFIX . '_API_R_FUNC_NAME'))
            $fvar = constant(IBC1_PREFIX . '_API_R_FUNC_NAME');
        else
            $fvar = 'f'; // default function variable: f
        if (defined(IBC1_PREFIX . '_API_R_FUNC_SRC') &&
                constant(IBC1_PREFIX . '_API_R_FUNC_SRC') == 'POST')
            $fsrc = '_POST';
        else
            $fsrc = '_GET'; // default function name source: GET
        if (defined(IBC1_PREFIX . '_API_R_PARAM_SRC') &&
                constant(IBC1_PREFIX . '_API_R_PARAM_SRC') == 'GET')
            $psrc = '_GET';
        else
            $psrc = '_POST'; // default parameter source: POST

        if (isset($GLOBALS[$fsrc][$fvar])) {
            $f = $GLOBALS[$fsrc][$fvar];
            if ($fsrc == $psrc) // function name stored with parameters
                unset($GLOBALS[$psrc][$fvar]); // prevent confusion
        } else {// miss function name
            $f = '';
        }

        require 'APICaller.class.php';
        $api = new APICaller();
        echo json_encode($api->invoke($f, $GLOBALS[$psrc]));

        exit;
    }

}