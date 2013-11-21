<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require 'config.php';

define('DB3_Author', 'GuZhiji Studio');
define('DB3_Mail', 'gu_zhiji@163.com');
define('DB3_Version', '3.0');
define('DB3_Time', '2010-2013');

if (!defined("dbUploadPath"))
    define("dbUploadPath", "uploadedfiles");

require 'modules/core/core1.lib.php';
LoadIBC1Lib('common', 'framework');

//-----------------------------------

function GetAds($adsname) {
    if ($adsname == "")
        return "";
    $path = "ads/$adsname.tpl";
    if (!is_file($path))
        return "";
    return file_get_contents($path);
}

function GetSettingValue($settingName) {
    global $_settingReader;
//if (!is_a($_settingReader, "PHPCacheReader")) {
    if (!isset($_settingReader)) {
        $_settingReader = new PHPCacheReader("cache", "settings");
    }
    return $_settingReader->GetValue($settingName);
}

function GetVisitorCount() {
    global $_visitorCount;
    if (isset($_visitorCount))
        return $_visitorCount;
    $countfile = "cache/counter";
    if (is_file($countfile)) {
        $_visitorCount = intval(file_get_contents($countfile));
        if ($_visitorCount > 0) {
            if (isset($_SERVER["HTTP_REFERER"])) {
                if (isset($_SERVER["HTTP_HOST"])) {
                    if (strpos($_SERVER["HTTP_REFERER"], $_SERVER["HTTP_HOST"]) > 0)
                        return $_visitorCount;
                }else if (strpos($_SERVER["HTTP_REFERER"], $_SERVER["SERVER_NAME"]) > 0) {
                    return $_visitorCount;
                }
            } else if (strCookie("Visited") == "TRUE") {
                return $_visitorCount;
            }
        }
    } else {
        $_visitorCount = 0;
    }
    $_visitorCount++;
    @file_put_contents($countfile, strval(intval($_visitorCount)));
    setcookie(dbPrefix . "_Visited", "TRUE", time() + 60 * 60);
    return $_visitorCount;
}

//------------------------------------------------------------------
function Len_Control($content, $max_len) {
    if (!is_int($max_len)) {
        return $content;
    } else {
        if (function_exists("mb_strlen")) {
            if (mb_strlen($content, IBC1_ENCODING) > $max_len)
                return mb_substr($content, 0, $max_len, IBC1_ENCODING) . "...";
        }else {
            if (strlen($content) > $max_len)
                return substr($content, 0, $max_len) . "...";
        }
        return $content;
    }
}

//------------------------------------------------------------------
function ProcessKeywords($keywords, $separator) {
    if ($keywords == NULL)
        return "";
    $keywords = str_replace('"', $separator, $keywords);
    $keywords = str_replace('\'', $separator, $keywords);
    $keywords = str_replace(' ', $separator, $keywords);
    $keywords = str_replace(';', $separator, $keywords);
    $keywords = str_replace(',', $separator, $keywords);
    $keywords = str_replace('.', $separator, $keywords);
    $keywords = str_replace('|', $separator, $keywords);
    $keywords = str_replace('“', $separator, $keywords);
    $keywords = str_replace('”', $separator, $keywords);
    $keywords = str_replace('‘', $separator, $keywords);
    $keywords = str_replace('’', $separator, $keywords);
    $keywords = str_replace('　', $separator, $keywords);
    $keywords = str_replace('；', $separator, $keywords);
    $keywords = str_replace('，', $separator, $keywords);
    $keywords = str_replace('。', $separator, $keywords);
    while (strpos($keywords, $separator . $separator) !== FALSE)
        $keywords = str_replace($separator . $separator, $separator, $keywords);

    return $keywords;
}

function PrepareSearchKey($keys) {
    $keys = ProcessKeywords($keys, '%');
    if ($keys != '') {
        if (substr($keys, 0, 1) != '%')
            $keys = '%' . $keys;
        if (substr($keys, -1) != '%')
            $keys .= '%';
        return $keys;
    }
    return '';
}

//------------------------------------------------------------------
function UpdateVersion($name) {
    require_once("modules/data/setting.module.php");
    $s = array($name => time());
    return SaveSettings($s);
}

//------------------------------------------------------------------
function IsFileTypeAllowed($type) {
    static $UFileTypes = NULL;
    if ($UFileTypes == NULL)
        $UFileTypes = explode(";", GetSettingValue("upload_filetypes"));
    foreach ($UFileTypes as $ft) {
        if (strlen($ft) > 0) {
            if (strtolower($type) == strtolower($ft))
                return TRUE;
        }
    }
    return FALSE;
}

//################################

function DB3_Button($type, $text, $params = array()) {
    $params['class'] = 'db3_button1';
    $params['class_selected'] = 'db3_button1_selected';
    return CreateButton($type, $text, $params);
}

function DB3_Link($text, $url, $params = array()) {
    $params['url'] = $url;
    return CreateButton('link', $text, $params);
}

function DB3_Operation_IsConfirmed($operation) {
    $p = DB3_Passport();
    $confirmed = $p->GetValue('Confirmation') === $operation &&
            isset($_GET['confirmed']);
    $p->RemoveValue('Confirmation');
//    $k = dbPrefix . '_Confirmation';
//    $confirmed = isset($_SESSION[$k]) &&
//            $_SESSION[$k] === $operation &&
//            isset($_GET['confirmed']);
//    unset($_SESSION[$k]);
    return $confirmed;
}

function DB3_Operation_ToConfirm($operation) {
    $p = DB3_Passport();
    $p->SetValue('Confirmation', $operation);
//    $_SESSION[dbPrefix . '_Confirmation'] = $operation;
}

function DB3_Passport() {
    global $_passport;
    if (!isset($_passport)) {
        LoadIBC1Class('UserPassport', 'data.user');
        $_passport = new UserPassport(DB3_SERVICE_USER);
    }
    return $_passport;
}

function DB3_ContentType($type) {
    global $_contenttypes;
    if (!isset($_contenttypes))
        $_contenttypes = include('conf/contenttypes.conf.php');
    if (isset($_contenttypes[$type]))
        return $_contenttypes[$type];
    return array();
}

function DB3_ContentType_Data($type, $key) {
    $data = DB3_ContentType($type);
    if (isset($data[$key]))
        return $data[$key];
    return NULL;
}

function DB3_Pages() {
    global $_pageConfig;
    if (!isset($_pageConfig))
        $_pageConfig = include('conf/pages.conf.php');
    return array_keys($_pageConfig);
}

function DB3_Page_Config($page, $key) {
    global $_pageConfig;
    if (!isset($_pageConfig))
        $_pageConfig = include('conf/pages.conf.php');
    if (isset($_pageConfig[$page][$key]))
        return $_pageConfig[$page][$key];
    return NULL;
}

function DB3_URL($page, $module = '', $function = '', $params = array()) {
    // generate querystring
    $q = '';
    if (!empty($module))
        $q .= 'module=' . $module;
    if (!empty($function))
        $q .= '&function=' . $function;
    foreach ($params as $k => $v) {
        if (!empty($q))
            $q .= '&';
        $q .= $k . '=' . urlencode($v);
    }
    // get path
//    $path = DB3_Page_Config($page, 'path');
    $path = GetSysResPath(DB3_Page_Config($page, 'path'), '');
//    if (substr($path, 0, 1) != '/') {
//        if (defined('DB3_ROOT_WEB'))
//            $path = DB3_ROOT_WEB . '/' . $path;
//        else
//            $path = '/' . $path;
//    }
    if (empty($q))
        return $path;
    else
        return $path . '?' . $q;
}

function DB3_URL_Copy($params = array()) {

    if (empty($_GET) && empty($params))
        return $_SERVER['SCRIPT_NAME'];

    // modify GET parameters
    $q = $_GET;
    foreach ($params as $k => $v) {
        if (empty($k))
            continue;
        if (empty($v) && $v !== 0) {
            if (isset($q[$k]))
                unset($q[$k]);
            continue;
        }
        $q[$k] = urlencode($v);
    }

    // generate querystring
    $qs = '';
    foreach ($q as $k => $v) {
        if (!empty($qs))
            $qs .= '&';
        $qs .= $k . '=' . urlencode($v);
    }

    return $_SERVER['SCRIPT_NAME'] . '?' . $qs;
}