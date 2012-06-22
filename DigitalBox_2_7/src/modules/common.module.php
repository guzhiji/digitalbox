<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require("config.php");

define("dbAuthor", "GuZhiji Studio");
define("dbMail", "gu_zhiji@163.com");
define("dbVersion", "2.7");
define("dbTime", "2011-2012");

define("dbEncoding", "utf-8");

if (!defined("dbUploadPath"))
    define("dbUploadPath", "uploadedfiles");

//------------------------------------------------------------------
//database connection
//------------------------------------------------------------------
//function db_connect() {
//    global $_connid;
//    if (!isset($_connid) || empty($_connid)) {
//        $_connid = mysql_connect(dbMySQLHostName, dbMySQLUserName, dbMySQLPassword);
//    }
//    return $_connid;
//}

function db_connect() {
    global $_connid;
    if (!isset($_connid) || empty($_connid)) {
        $_connid = mysql_connect(dbMySQLHostName, dbMySQLUserName, dbMySQLPassword);
        if ($_connid && !mysql_select_db(dbDatabaseName, $_connid)) {
            mysql_close($_connid);
            $_connid = FALSE;
        }
    }
    return $_connid;
}

function db_close() {
    global $_connid;
    if (isset($_connid) && !empty($_connid)) {
        $r = mysql_close($_connid);
        $_connid = NULL;
        return $r;
    }
    return FALSE;
}

//function db_query($sql, $args = NULL) {
//
//    $connid = db_connect();
//    if ($args === NULL || count($args) == 0) {
//        return mysql_db_query(dbDatabaseName, $sql, $connid);
//    } else {
//        $args_[0] = &$sql;
//        $c = count($args);
//        for ($a = 0; $a < $c; $a++) {
//            $args_[$a + 1] = &$args[$a];
//            if (is_string($args_[$a + 1]))
//                $args_[$a + 1] = mysql_real_escape_string($args_[$a + 1]);
//        }
//        return mysql_db_query(dbDatabaseName, call_user_func_array("sprintf", $args_), $connid);
//    }
//}

function db_query($sql, $args = NULL) {

    $connid = db_connect();
    if (empty($args)) {

        //sql debug
        //echo $sql.";";
        //return mysql_db_query(dbDatabaseName, $sql, $connid);
        return mysql_query($sql, $connid);
    } else {
        //$args_[0] = &$sql;
        $args_ = array(&$sql);
        $c = count($args);
        for ($a = 0; $a < $c; $a++) {
            $args_[$a + 1] = &$args[$a];
            if (is_string($args_[$a + 1]))
                $args_[$a + 1] = mysql_real_escape_string($args_[$a + 1]);
        }

        //sql debug
        //echo call_user_func_array("sprintf",$args_).";";
        //return mysql_db_query(dbDatabaseName, call_user_func_array("sprintf", $args_), $connid);
        return mysql_query(call_user_func_array("sprintf", $args_), $connid);
    }
}

function db_result($result) {
    $list = array();
    while ($row = mysql_fetch_array($result)) {
        $list[] = $row;
    }
    return $list;
}

function db_free($result) {
    mysql_free_result($result);
}

//------------------------------------------------------------------
//resource management
//------------------------------------------------------------------
require("modules/cache/PHPCacheReader.class.php");

function GetSiteURL() {
    global $_sitePath;
    if (isset($_sitePath))
        return $_sitePath;
    $phpfile = isset($_SERVER["PHP_SELF"]) ? $_SERVER["PHP_SELF"] : $_SERVER["SCRIPT_NAME"];
    $_sitePath = "http://" . $_SERVER["HTTP_HOST"] . substr($phpfile, 0, strrpos($phpfile, "/") + 1);
    return $_sitePath;
}

function GetSystemPath() {
    global $_sysPath;
    if (isset($_sysPath))
        return $_sysPath;
    $a = str_replace("\\", "/", $_SERVER["SCRIPT_FILENAME"]); //for windows
//$a = str_replace("\\", "/",$_SERVER["PATH_TRANSLATED"]);
    if (substr($a, -1) != "/") {
        $b = strrpos($a, "/");
        if ($b > 0)
            $a = substr($a, 0, $b);
    }
    $_sysPath = $a;
    return $_sysPath;
}

/*
  function GetSystemPath($path){
  #$a = $_SERVER["PATH_TRANSLATED"];
  $a = $_SERVER["SCRIPT_FILENAME"];
  return substr($a,0,strlen($a)-strlen($path));
  }
 */

function GetSysResPath($resname, $path) {
    $syspath = GetSystemPath() . "/";
    $sysrespath = $path . "/" . $resname;
    if (is_file($syspath . $sysrespath))
        return $sysrespath;
    return "";
}

/**
 * get theme id
 * 
 * choose user preferred one or the system default one
 * @return int
 */
function GetThemeID() {
    global $_themeID;
    if (isset($_themeID))
        return $_themeID;

    $id = strCookie("Style");

    if ($id == "")
        $_themeID = GetSettingValue("default_style");
    else
        $_themeID = intval($id);

    return $_themeID;
}

/**
 * get a path to a resource file with a theme
 * 
 * @param string $resname   resource file name
 * @param string $restype   images, stylesheets, scripts
 * @param int $themeid
 * @return string 
 */
function GetThemeResPath($resname, $restype, $themeid = NULL) {

    if ($themeid === NULL)
        $themeid = GetThemeID();
    $syspath = GetSystemPath() . "/";
    $sysrespath = $restype . "/" . $resname;
    $themerespath = "themes/" . $themeid . "/" . $sysrespath;
    if (is_file($syspath . $themerespath))
        return $themerespath;
    if (is_file($syspath . $sysrespath))
        return $sysrespath;
    return "";
}

/**
 * get language code according to http request and system default setting
 * 
 * @return string
 */
function GetLang() {
    global $_lang;
    if (isset($_lang))
        return $_lang;
    if (strCookie("Lang") != "" && is_dir("lang/" . strCookie("Lang"))) {
        $_lang = strCookie("Lang");
        return $_lang;
    }
    $l = explode(";", $_SERVER["HTTP_ACCEPT_LANGUAGE"]);
    $l = explode(",", $l[0]);
    foreach ($l as $lang) {
        $lang = strtolower($lang);
        $filename = "lang/" . $lang;
        if (is_dir($filename)) {
            $_lang = $lang;
            return $_lang;
        }
        $pos = strpos($lang, "-");
        if ($pos > 0) {
            //e.g. zh-cn
            $lang = substr($lang, 0, $pos);
            if (is_dir("lang/" . $lang)) {
                $_lang = $lang;
                return $_lang;
            }
        }
    }
    $_lang = strtolower(GetSettingValue("default_lang"));
    return $_lang;
}

/**
 * get a directory path where cache files are stored
 * @param bool $makedir
 * @param int $themeid
 * @param string $lang 
 */
function GetCachePath($makedir = FALSE, $themeid = NULL, $lang = NULL) {
    if (empty($lang))
        $lang = GetLang();
    if (empty($themeid))
        $themeid = GetThemeID();

    $lang = strtolower($lang);

    $syspath = GetSystemPath() . "/";
    $cachepath = "cache/" . $themeid . "/" . $lang;

    if (is_dir($syspath . $cachepath)) {
        return $cachepath;
    } else if ($makedir) {
        if (!is_dir($syspath . "cache/" . $themeid))
            mkdir($syspath . "cache/" . $themeid);
        if (mkdir($syspath . $cachepath))
            return $cachepath;
    }
    return "";
}

/**
 * 
 * @param string $tplname
 * @param string $classname
 * @param int $themeid
 * @param string $lang
 * @return string 
 */
function GetTplPath($tplname, $classname = NULL, $themeid = NULL, $lang = NULL) {
    if (empty($themeid))
        $themeid = GetThemeID();

    $syspath = GetSystemPath() . "/";
    $sysrespath = "templates/";
    $themerespath = "themes/" . $themeid . "/templates/";

    if (!empty($classname))
        $classname.="/";
    else
        $classname = "";

    if ($lang == NULL || $lang != "neutral") {
        if ($lang == NULL)
            $lang = GetLang();
        else
            $lang = strtolower($lang);
        if (is_file($syspath . $themerespath . $classname . $lang . "/" . $tplname . ".tpl")) {
            return $themerespath . $classname . $lang . "/" . $tplname . ".tpl";
        }
        if (is_file($syspath . $sysrespath . $classname . $lang . "/" . $tplname . ".tpl")) {
            return $sysrespath . $classname . $lang . "/" . $tplname . ".tpl";
        }
    }

    if (is_file($syspath . $themerespath . $classname . $tplname . ".tpl")) {
        return $themerespath . $classname . $tplname . ".tpl";
    }
    if (is_file($syspath . $sysrespath . $classname . $tplname . ".tpl")) {
        return $sysrespath . $classname . $tplname . ".tpl";
    }

    return "";
}

/**
 * read template file
 * @param string $tplname
 * @param string $classname
 * @param int $themeid
 * @param string $lang
 * @return string 
 */
function GetTemplate($tplname, $classname = NULL, $themeid = NULL, $lang = NULL) {
    $path = GetTplPath($tplname, $classname, $themeid, $lang);
    if ($path == "")
        return "";
    return file_get_contents($path);
}

/**
 * read template, pass parameters to it and generate HTML
 * @param string $tplname
 * @param array $vars
 * @param string $classname
 * @param int $themeid
 * @param string $lang
 * @return string 
 * @see GetTemplate()
 * @see Tpl2HTML()
 */
function TransformTpl($tplname, $vars, $classname = NULL, $themeid = NULL, $lang = NULL) {
    $tpl = GetTemplate($tplname, $classname, $themeid, $lang);

    return Tpl2HTML($tpl, $vars);
}

/**
 * pass parameters to the template and generate HTML
 * @param string $tpl   content of a template
 * @param array $vars   variables to be assigned
 * <code>
 * array(
 *     [variable1 name]=>[variable1 value],
 *     [variable2 name]=>[variable2 value],
 *     ...
 * )
 * </code>
 * @return string 
 */
function Tpl2HTML($tpl, $vars) {
    foreach ($vars as $varname => $varvalue) {
        $varvalue = str_replace("\\", "\\\\", $varvalue);
        $varvalue = str_replace("\"", "\\\"", $varvalue);
        $varvalue = str_replace("\$", "\\\$", $varvalue);
        eval("\$$varname=\"$varvalue\";");
    }
    $tpl = str_replace("\\", "\\\\", $tpl);
    $tpl = str_replace("\"", "\\\"", $tpl);
    eval("\$tpl=\"$tpl\";");
    return $tpl;
}

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

function GetLangData($key, $category = NULL) {
    global $_langData;
    global $_lang;
    if (!isset($_lang) || !isset($_langData) || !isset($_langData[$_lang][$key])) {
        if (empty($category))
            $category = "main";
        $_lang = GetLang();
        $filename = "lang/" . $_lang . "/" . $category . ".lang.php";
        if (is_file($filename)) {
            require $filename;
        } else {
            $_lang = GetSettingValue("default_lang");
            $filename = "lang/" . $_lang . "/" . $category . ".lang.php";
            require $filename;
        }
    }

    return $_langData[$_lang][$key];
}

function FormatPath($path, $filename = "") {
    $path = str_replace("\\", "/", $path); //for windows
    if (substr($path, -1) != "/")
        $path.="/";
    return $path . $filename;
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
function ContentTip($info_name, $info_type, $info_channel, $info_class, $info_visitors, $info_time) {
    $tt = GetLangData("name") . ": " . $info_name . " \n";
    $tt .= GetLangData("type") . ": " . $info_type . " \n";
    if ($info_channel != "")
        $tt .= GetLangData("channel") . ": " . $info_channel . " \n";
    if ($info_class != "")
        $tt .= GetLangData("class") . ": " . $info_class . " \n";
    $tt .= GetLangData("click") . ": " . $info_visitors . " \n";
    $tt .= GetLangData("time") . ": " . $info_time . " ";
    return htmlspecialchars($tt);
}

function ClassTip($info_name, $info_type, $info_parent = "") {
    $ct = GetLangData("name") . ": " . $info_name . " \n";
    $ct .= GetLangData("type") . ": " . $info_type . "[" . GetLangData("class") . "] \n";
    if ($info_parent != "")
        $ct .= GetLangData("channel") . ": " . $info_parent . " ";
    return htmlspecialchars($ct);
}

function ChannelTip($info_name, $info_type) {
    $ct = GetLangData("name") . ": " . $info_name . "\n";
    $ct .= GetLangData("type") . ": " . $info_type . "[" . GetLangData("channel") . "]";
    return htmlspecialchars($ct);
}

/**
 * get the type name of a type number
 * 
 * @param int $t	type number
 * <ul>
 * <li>0 - link</li>
 * <li>1 - article</li>
 * <li>2 - picture</li>
 * <li>3 - media</li>
 * <li>4 - software</li>
 * </ul>
 * @param int $language	language code
 * <ul>
 * <li>0 - user's language</li>
 * <li>1 - English</li>
 * </ul>
 */
function GetTypeName($t, $language) {
    $types = GetLangData("ContentTypes");
    /*
     * ContentTypes
     * 
     * array(
     *  0 => array([user's language], [en]),
     *  ...
     * )
     * 
     * or
     * 
     * array(
     *  0 => [en],
     *  ...
     * )
     */
//    $type = &$_langData[$l]["ContentTypes"][$t];
    $type = $types[$t];
    if (is_array($type))
        return $type[$language];
    return $type;
}

/**
 * convert type name to type number
 * 
 * @param string $typename  English type name
 * @return int 
 */
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
function PageCount($totalrecord, $pagesize) {
    $c = intval($totalrecord) / intval($pagesize);
    $ic = intval($c);
    if ($c > $ic)
        return $ic + 1;
    return $ic;
}

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
function CheckText($name, $max, $min, &$text, $ishtml = FALSE, $multiline = FALSE, $default = NULL) {
    $errors = "";
//filter & convert
    require_once("modules/filters.lib.php"); //TODO load filter lib at other places
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
function UpdateVersion($name) {
    require_once("modules/data/setting.module.php");
    $s = array($name => time());
    return SaveSettings($s);
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
        return round($size, 3) . " " . $unit;
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
function toScriptString($str, $isphp = FALSE) {
    $str = str_replace("\\", "\\\\", $str);
    $str = str_replace("\"", "\\\"", $str);
    if ($isphp)
        $str = str_replace("\$", "\\\$", $str);
    return "\"$str\"";
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
