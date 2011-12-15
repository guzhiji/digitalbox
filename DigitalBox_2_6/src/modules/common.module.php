<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/common.module.php
  ------------------------------------------------------------------
 */
require_once("config.php");

define("dbAuthor", "GuZhiji Studio");
define("dbMail", "gu_zhiji@163.com");
define("dbVersion", "2.6");
define("dbTime", "2011");

define("dbEncoding", "utf-8");

if (!defined("dbUploadPath"))
    define("dbUploadPath", "uploadedfiles");

//------------------------------------------------------------------
function GetSystemPath() {
    $a = str_replace("\\", "/", $_SERVER["SCRIPT_FILENAME"]); //for windows
    //$a = str_replace("\\", "/",$_SERVER["PATH_TRANSLATED"]);
    if (substr($a, -1) != "/") {
        $b = strrpos($a, "/");
        if ($b > 0)
            $a = substr($a, 0, $b);
    }
    return $a;
}

/*
  function GetSystemPath($path){
  #$a = $_SERVER["PATH_TRANSLATED"];
  $a = $_SERVER["SCRIPT_FILENAME"];
  return substr($a,0,strlen($a)-strlen($path));
  }
 */

//------------------------------------------------------------------
function ContentTip($info_name, $info_type, $info_channel, $info_class, $info_visitors, $info_time) {
    $tt = "名称：" . $info_name . " \n";
    $tt .= "类型：" . $info_type . " \n";
    if ($info_channel != "")
        $tt .= "频道：" . $info_channel . " \n";
    if ($info_class != "")
        $tt .= "栏目：" . $info_class . " \n";
    $tt .= "点击：" . $info_visitors . " \n";
    $tt .= "时间：" . $info_time . " ";
    return $tt;
}

//------------------------------------------------------------------

function ClassTip($info_name, $info_type, $info_parent="") {
    $ct = "栏目名称：" . $info_name . " \n";
    $ct .= "内容类型：" . $info_type . " \n";
    if ($info_parent != "")
        $ct .= "所属频道：" . $info_parent . " ";
    return $ct;
}

//------------------------------------------------------------------
function ChannelTip($info_name, $info_type) {
    $ct = "频道名称：" . $info_name . "\n";
    $ct .= "内容类型：" . $info_type;
    return $ct;
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
//function GetSettingValue($settingName) {
//
//    //load settings
//    if (!isset($_SettingData)) {
//        if (!is_file("settings.php")) {
//            GLOBAL $connid;
//            require_once("modules/setting.module.php");
//            RefreshSettings($connid);
//        }
//        require("settings.php");
//    }
//    //check name
//    if ($settingName === NULL || $settingName == "")
//        return "";
//
//    if (!isset($_SettingData[$settingName])) {
//
//        //load customized style info
//        if (!isset($_SettingData["style_id"])) {
//
//            $styleid = strCookie("Style");
//
//            if ($styleid == "")
//                $styleid = $_SettingData["default_style"];
//            else
//                $styleid = intval($styleid);
//
//            $_SettingData["style_id"] = $styleid;
//            $_SettingData["style_name"] = $_SettingData["_style_name"][$styleid];
//            //$_SettingData["style_imagefolder"]=$_SettingData["_style_imagefolder"][$styleid];
//            //$_SettingData["style_cssfile"]=$_SettingData["_style_cssfile"][$styleid];
//            $_SettingData["style_imagefolder"] = "themes/$styleid/images/";
//            $_SettingData["style_cssfile"] = "themes/$styleid/$styleid.css";
//        }
//        if (!isset($_SettingData[$settingName]))
//            return "";
//    }
//    return $_SettingData[$settingName];
//}
function GetSettingValue($settingName) {
    if ($settingName == "style_id") {
        $styleid = strCookie("Style");

        if ($styleid == "")
            return GetCachedData("default_style");
        else
            return intval($styleid);
    }
    return GetCachedData($settingName);
}

function GetCachedData($key, $category="settings") {
    if (!isset($_cachedData)) {
        $cachefile = "cache/{$category}.php";
        if (!is_file($cachefile))
            return "";
        GLOBAL $_cachedData;
        require($cachefile);
    }
    if (!isset($_cachedData[$category]))
        return "";
    if (!isset($_cachedData[$category][$key]))
        return "";
    return $_cachedData[$category][$key];
}

//------------------------------------------------------------------
/**
 * get the type name of a type code in either Chinese or English
 * @param int $t	type code
 * <ul>
 * <li>0 - link</li>
 * <li>1 - article</li>
 * <li>2 - picture</li>
 * <li>3 - media</li>
 * <li>4 - software</li>
 * </ul>
 * @param int $language	language code
 * <ul>
 * <li>0 - Chinese</li>
 * <li>1 - English</li>
 * </ul>
 */
function GetTypeName($t, $language) {
    switch ($t) {
        case 2:
            return select_language("图片", "picture", $language);
        case 3:
            return select_language("媒体", "media", $language);
        case 4:
            return select_language("软件", "software", $language);
        case 0:
            return select_language("自定义链接", "link", $language);
        default:
            return select_language("文章", "article", $language);
    }
}

function select_language($zh_text, $en_text, $language) {
    switch ($language) {
        case 0:
            return $zh_text;
        case 1:
            return $en_text;
    }
}

//------------------------------------------------------------------
function GetTypeNumber($typename) {
    switch (strtolower($typename)) {
        case "article":
            return 1;
        case "picture":
            return 2;
        case "media":
            return 3;
        case "software":
            return 4;
        default:
            return 0;
    }
}

//------------------------------------------------------------------
function PageCount($totalrecord, $pagesize) {
    $c = intval($totalrecord) / intval($pagesize);
    $ic = intval($c);
    if ($c > $ic)
        return $ic + 1;
    return $ic;
}

//------------------------------------------------------------------
function PageNumber($n, $maxn) {
    $pagen = intval($n);
    if (strlen($n) > 0) {
        if ($pagen < 1 || $pagen > intval($maxn))
            return 1;
        return $pagen;
    }else
        return 1;
}

//------------------------------------------------------------------
function ProcessKeywords($keywords, $separator) {
    if ($keywords == NULL)
        return "";
    $keywords = str_replace("\"", $separator, $keywords);
    $keywords = str_replace("'", $separator, $keywords);
    $keywords = str_replace(" ", $separator, $keywords);
    $keywords = str_replace(";", $separator, $keywords);
    $keywords = str_replace(",", $separator, $keywords);
    $keywords = str_replace(".", $separator, $keywords);
    $keywords = str_replace("|", $separator, $keywords);
    $keywords = str_replace("“", $separator, $keywords);
    $keywords = str_replace("”", $separator, $keywords);
    $keywords = str_replace("‘", $separator, $keywords);
    $keywords = str_replace("’", $separator, $keywords);
    $keywords = str_replace("　", $separator, $keywords);
    $keywords = str_replace("；", $separator, $keywords);
    $keywords = str_replace("，", $separator, $keywords);
    $keywords = str_replace("。", $separator, $keywords);
    while (strpos($keywords, $separator . $separator) !== FALSE)
        $keywords = str_replace($separator . $separator, $separator, $keywords);

    return $keywords;
}

function PrepareSearchKey($keys) {
    $keys = ProcessKeywords($keys, "%");
    if ($keys != "") {
        if (substr($keys, 0, 1) != "%")
            $keys = "%" . $keys;
        if (substr($keys, -1) != "%")
            $keys .= "%";
        return $keys;
    }
    return "";
}

//------------------------------------------------------------------
/**
 * check text
 * @param string $name
 * @param int $max
 * @param int $min
 * @param string $text
 * @param bool $ishtml
 * @param bool $multiline
 * @param mixed $default
 * @return string	error information
 */
function CheckText($name, $max, $min, &$text, $ishtml=FALSE, $multiline=FALSE, $default=NULL) {
    $errors = "";
    //filter & convert
    if (!$ishtml) {
        $text = Text2HTML($text, $multiline);
    } else {
        $text = CompressHTML($text);
    }
    //get length
    $l = strlen($text);
    //check length
    if ($l == 0 || $l < $min) {
        if ($min > 0) {
            if ($default == NULL) {
                if ($l == 0) {
                    $errors .= "{$name}不能为空;";
                } else {
                    $errors .= "{$name}过短（最少{$min}字符）;";
                }
            } else {
                $text = $default;
            }
        }
        //min==0&&l==0
    } else if ($l > $max) {
        $errors .= "{$name}过长（最多{$max}字符）;";
    }
    return $errors;
}

function Text2HTML($text, $multiline) {
    $text = str_replace("&", "&amp;", $text);
    $text = str_replace("<", "&lt;", $text);
    $text = str_replace(">", "&gt;", $text);
    $text = str_replace("  ", "&nbsp;&nbsp;", $text);
    if ($multiline) {
        $text = str_ireplace("\n", "<br />", $text);
        $text = str_ireplace("\r", "<br />", $text);
    } else {
        $text = SingleLineText($text);
    }
    return $text;
}

function SingleLineText($text) {
    $text = str_replace("\r", " ", $text);
    $text = str_replace("\n", " ", $text);
    return $text;
}

function CompressHTML($html) {
    $html = str_replace("\r", " ", $html);
    $html = str_replace("\n", " ", $html);
    while (strpos($html, "\t") !== FALSE) {
        $html = str_replace("\t", " ", $html);
    }
    while (strpos($html, "     ") !== FALSE) {
        $html = str_replace("     ", " ", $html);
    }
    while (strpos($html, "  ") !== FALSE) {
        $html = str_replace("  ", " ", $html);
    }
    return $html;
}

//------------------------------------------------------------------
function TextForInputBox($text) {
    $text = str_replace("\n", "", $text);
    $text = str_replace("\r", "", $text);
    $text = str_replace("&", "&amp;", $text);
    $text = str_replace("\"", "&quot;", $text); //<input value="[text without quotation marks]" />
    $text = str_replace("<", "&lt;", $text);
    $text = str_replace(">", "&gt;", $text);
    return $text;
}

function TextForTextArea($text) {
    //$text = str_ireplace("</P>", "",$text);
    //$text = str_ireplace("<P>", "\n\n",$text);
    //$text = str_ireplace("<BR>", "\n",$text);
    //$text = str_replace("&","&amp;",$text);
    $text = str_replace("<", "&lt;", $text);
    $text = str_replace(">", "&gt;", $text);
    return $text;
}

function HTMLForInputBox($html) {
    $html = str_replace("\n", "", $html);
    $html = str_replace("\r", "", $html);
    $html = str_replace("&", "&amp;", $html);
    $html = str_replace("\"", "&quot;", $html);
    return $html;
}

function HTMLForTextArea($html) {
    $html = str_replace("&", "&amp;", $html);
    $html = str_replace("<", "&lt;", $html);
    $html = str_replace(">", "&gt;", $html);
    return $html;
}

//------------------------------------------------------------------
function GetAds($adsname) {
    if ($adsname == "")
        return "";
    $path = "ads/$adsname.tpl";
    if (!is_file($path))
        return "";
    return file_get_contents($path);
}

//------------------------------------------------------------------
/**
 * read template file
 * @param string $tplname
 * @return string 
 */
function GetTemplate($tplname) {
    $path = GetResPath($tplname . ".tpl", "templates", GetSettingValue("style_id"));

    if ($path == "")
        return "";
    return file_get_contents($path);
}

/**
 * pass parameters to the template and generate HTML
 * @param string $tpl
 * @param array $vars
 * array(
 * "[parameter name1]"=>"[value1]",
 * "[parameter name2]"=>"[value2]",
 * ...
 * )
 * @param string $tplclass
 * @return string 
 */
function Tpl2HTML($tpl, $vars, $tplclass="") {
    foreach ($vars as $varname => $varvalue) {
        $varvalue = str_replace("\\", "\\\\", $varvalue);
        $varvalue = str_replace("\"", "\\\"", $varvalue);
        if ($tplclass != "") {
            $varname = $tplclass . "_" . $varname;
        }
        eval("\$$varname=\"$varvalue\";");
    }
    $tpl = str_replace("\\", "\\\\", $tpl);
    $tpl = str_replace("\"", "\\\"", $tpl);
    eval("\$tpl=\"$tpl\";");
    return $tpl;
}

function TransformTpl($tplname, $vars, $tplclass="") {
    $tpl = GetTemplate($tplname);
    if ($tplclass != "") {
        return Tpl2HTML($tpl, $vars, $tplclass);
    } else {
        return Tpl2HTML($tpl, $vars);
    }
}

//------------------------------------------------------------------
function GetResPath($resname, $restype, $themeid) {
    $syspath = GetSystemPath() . "/";
    $sysrespath = $restype . "/" . $resname;
    $themerespath = "themes/" . $themeid . "/" . $sysrespath;
    if (is_file($syspath . $themerespath))
        return $themerespath;
    if (is_file($syspath . $sysrespath))
        return $sysrespath;
    return "";
}

//------------------------------------------------------------------
function ErrorList(&$errortext) {
    $et = "";
    if ($errortext != "") {
        $et .= "<ul>";
        if (strpos($errortext, ";")) {
            $errorset = explode(";", $errortext);
            foreach ($errorset as $erroritem)
                if (strlen($erroritem) > 0)
                    $et .= "<li>$erroritem</li>";
        }else
            $et .= "<li>$errortext</li>";
        $et .= "</ul>";
    }
    return $et;
}

//------------------------------------------------------------------
function GetFileExt($filename) {
    $a = strpos($filename, ".");
    if ($a > 0)
        return substr($filename, $a + 1, strlen($filename) - $a - 1);
    return "";
}

//------------------------------------------------------------------
function GetSizeWithUnit($size) {
    if ($size <= 1000) {
        return intval($size) . " Bytes";
    } else {
        $size = $size / 1024;
        if ($size <= 1000) {
            $unit = "KB";
        } else {
            $size = $size / 1024;
            if ($size <= 1000) {
                $unit = "MB";
            } else {
                $size = $size / 1024;
                $unit = "GB";
            }
        }
        return number_format($size, 3) . " " . $unit;
    }
}

function Size2Bytes($size, $unit) {
    $size = doubleval($size);
    switch (strtoupper($unit)) {
        case "KB":
            $size *= 1024;
        case "MB":
            $size *= 1024;
        case "GB":
            $size *= 1024;
    }
    return round($size);
}

//------------------------------------------------------------------
function PageRedirect($page) {
    $page = str_replace("\\", "/", $page);
    $url = $_SERVER["SCRIPT_NAME"];
    $url = substr($url, 0, strrpos($url, "/"));
    while (substr($page, 0, 3) == "../") {
        if (!strrpos($url, "/"))
            break;
        $url = substr($url, 0, strrpos($url, "/"));
        $page = substr($page, 3, strlen($page) - 3);
    }
    if ($url == "")
        $url = "/";
    if ($page != "")
        if (substr($page, 0, 1) != "/")
            $page = "/" . $page;
    $url = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $url . $page;
    header("Location: " . $url);
    exit();
}

//------------------------------------------------------------------
function GetSiteURL() {
    $phpfile = $_SERVER["PHP_SELF"] ? $_SERVER["PHP_SELF"] : $_SERVER["SCRIPT_NAME"];
    return "http://" . $_SERVER["HTTP_HOST"] . substr($phpfile, 0, strrpos($phpfile, "/") + 1);
}

//------------------------------------------------------------------
function strGet($strname) {
    if (isset($_GET[$strname])) {
        if (get_magic_quotes_gpc()) {
            return stripslashes($_GET[$strname]);
        } else {
            return $_GET[$strname];
        }
    }
    return "";
}

function strPost($strname) {
    if (isset($_POST[$strname])) {
        if (get_magic_quotes_gpc()) {
            return stripslashes($_POST[$strname]);
        } else {
            return $_POST[$strname];
        }
    }
    return "";
}

function strCookie($strname) {
    if (isset($_COOKIE[dbPrefix . "_" . $strname])) {
        if (get_magic_quotes_gpc()) {
            return stripslashes($_COOKIE[dbPrefix . "_" . $strname]);
        } else {
            return $_COOKIE[dbPrefix . "_" . $strname];
        }
    }
    return "";
}

function strSession($strname) {
    if (isset($_SESSION[dbPrefix . "_" . $strname]))
        return $_SESSION[dbPrefix . "_" . $strname];
    return "";
}

//------------------------------------------------------------------
function toScriptString($str) {
    $str = str_replace("\\", "\\\\", $str);
    $str = str_replace("\"", "\\\"", $str);
    return "\"$str\"";
}

//------------------------------------------------------------------
function LocationFormat($str) {
    $str = str_replace("\\", "/", trim($str));
    if (substr($str, -1) == "/")
        $str = substr($str, 0, strlen($str) - 1);
    return $str;
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

?>
