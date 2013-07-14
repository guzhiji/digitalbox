<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require("config.php");

define("dbAuthor", "GuZhiji Studio");
define("dbMail", "gu_zhiji@163.com");
define("dbVersion", "3.0");
define("dbTime", "2010-2013");

define("dbEncoding", "utf-8");

define('SERVICE_CATALOG', 'catalogtest');
define('SERVICE_USER', 'usertest');
define('SERVICE_ARTICLE', 'articletest');
define('SERVICE_COMMENT', 'commenttest');

if (!defined("dbUploadPath"))
    define("dbUploadPath", "uploadedfiles");

require 'modules/core/core1.lib.php';
LoadIBC1Lib('common', 'framework');

//-----------------------------------
//session

function getPassport() {
    global $_passport;
    if (!isset($_passport)) {
        LoadIBC1Class('UserPassport', 'datamodels.user');
        $_passport = new UserPassport(SERVICE_USER);
    }
    return $_passport;
}

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
            if (mb_strlen($content, dbEncoding) > $max_len)
                return mb_substr($content, 0, $max_len, dbEncoding) . "...";
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

function DB3_Button($type, $text, $params = array()) {
    if ($type == 'link') {
        $params['class'] = 'db3_button1';
        $params['class_selected'] = 'db3_button1_selected';
    }
    return CreateButton($type, $text, $params);
}

function DB3_Link($text, $url, $params = array()) {
    $params['url'] = $url;
    return CreateButton('link', $text, $params);
}
