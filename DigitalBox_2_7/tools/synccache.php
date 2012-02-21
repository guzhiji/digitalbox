<?php
/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require("modules/common.module.php");

function ShowCompareList() {
    //prepare an error message
    $message = "数据库连接错误";
    $rs = db_query("SELECT * FROM setting_info");
    if ($rs) {
        $nonempty = FALSE;
        $list = db_result($rs); //fetch settings from db
        db_free($rs);
        $dbs = array(); //prepare a hash table for the 2nd loop
        $html = "<table><tr><th>setting</th><th width=\"100\">database</th><th width=\"100\">cache</th></tr>";
        //1st loop: based on settings from db
        foreach ($list as $item) {
            //put name into the hash table without value only showing its existence
            //because if existing here, value is already checked here
            $dbs[$item["setting_name"]] = TRUE;
            //get the cached value, an empty string returned if not found
            $cvalue = GetSettingValue($item["setting_name"]);
            //convert
            $dbvalue = NULL;
            switch ($item["setting_type"]) {
                case 1:
                    $dbvalue = doubleval(str_replace(",", "", $item["setting_value"]));
                    $cvalue = doubleval($cvalue);
                    break;
                case 2:
                    $dbvalue = strtolower($item["setting_value"]);
                    $cvalue = $cvalue ? "true" : "false";
                    break;
                default:
                    $dbvalue = $item["setting_value"];
            }
            //compare
            if ($cvalue != $dbvalue) {
                $nonempty = TRUE;
                $html.= "<tr><th align=\"left\">{$item["setting_name"]}</th><td>{$dbvalue}</td><td>{$cvalue}</td></tr>";
            }
        }
        //fetch settings from cache file
        $reader = new PHPCacheReader("cache", "settings");
        $settingkeys = $reader->GetKeys();
        //2nd loop: based on cached settings
        foreach ($settingkeys as $key) {
            //check if not found in the settings from db
            if (!isset($dbs[$key])) {
                $value = $reader->GetValue($key);
                if (is_array($value))
                    continue;
                $nonempty = TRUE;
                if (is_bool($value))
                    $value = $value ? "true" : "false";
                $html.= "<tr><th align=\"left\">{$key}</th><td></td><td>{$value}</td></tr>";
            }
        }
        if ($nonempty) {
            //show compare list
            echo $html;
            echo "<tr><td></td>";
            echo "<td><input type=\"button\" class=\"button1\" value=\"to cache &gt;\" onclick=\"window.location='?function=sync2cache'\" /></td>";
            echo "<td><input type=\"button\" class=\"button1\" value=\"&lt; to database\" onclick=\"window.location='?function=sync2db'\" /></td>";
            echo "</tr></table>";
            return;
        } else {
            //same without an error
            $message = "所有数据经过比较都已经一致";
        }
    }
    //else db connection error
    //show message
    echo $message . "<br /><input type=\"button\" class=\"button1\" value=\"返回\" onclick=\"window.location='toolchecker.php'\"/>";
}

function Sync2Cache() {
    require("modules/cache/PHPCacheEditor.class.php");
    $cm = new PHPCacheEditor("cache", "settings");
    $rs = db_query("SELECT * FROM setting_info");
    if ($rs) {
        $list = db_result($rs);
        foreach ($list as $item) {
            switch ($item["setting_type"]) {
                case 1:
                    $cm->SetValue($item["setting_name"], doubleval($item["setting_value"]));
                    break;
                case 2:
                    if (strtolower($item["setting_value"]) == "true")
                        $cm->SetValue($item["setting_name"], TRUE);
                    else
                        $cm->SetValue($item["setting_name"], FALSE);
                    break;
                default:
                    $cm->SetValue($item["setting_name"], $item["setting_value"]);
            }
        }
        db_free($rs);
        try {
            $cm->Save();
            return TRUE;
        } catch (Exception $ex) {
            return FALSE;
        }
    }
    return FALSE;
}

function Sync2DB() {
    $reader = new PHPCacheReader("cache", "settings");
    $settingkeys = $reader->GetKeys();

    $e = FALSE;
    foreach ($settingkeys as $key) {
        $value = $reader->GetValue($key);
        if (is_array($value))
            continue;
        $type = 0;
        if (!is_string($value)) {
            if (is_bool($value)) {
                $type = 2;
                $value = $value ? "true" : "false";
            } else {
                $type = 1;
                if (is_int($value))
                    $value = strval($value);
                else
                    $value = strval(doubleval($value));
            }
        }
        if (!db_query("UPDATE setting_info SET setting_value=\"%s\",setting_type=\"%d\" WHERE setting_name=\"%s\"", array($value, $type, $key))) {
            $e = TRUE;
        }
        global $_connid;
        if (mysql_affected_rows($_connid) < 1) {
            $rs = db_query("SELECT \"true\" FROM setting_info WHERE setting_name=\"%s\"", array($key));
            if ($rs) {
                $list = db_result($rs);
                if (!isset($list[0])) {
                    if (!db_query("INSERT INTO setting_info (setting_name,setting_type,setting_value) VALUES (\"%s\",\"%d\",\"%s\")", array($key, $type, $value))) {
                        $e = TRUE;
                    }
                }
                db_free($rs);
            } else {
                $e = TRUE;
            }
        }
    }
    return !$e;
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>同步缓存 - 附加工具 - DigitalBox <?php echo dbVersion; ?></title>
        <link rel="stylesheet" href="stylesheets/main.css" />
        <link rel="Shortcut Icon" href="DigitalBoxIcon.ico" />
    </head>
    <body>
    <center>
        <table border=0 cellspacing=0 cellpadding=0 width=580>
            <tr>
                <td class="bg_top3" align="left">&nbsp;&nbsp;同步缓存 - 附加工具</td>
            </tr>
            <tr>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0" width="100%"
                           height="100%">
                        <tr>
                            <td class="bg_border"></td>
                            <td>
                                <table border="0" cellspacing="0" cellpadding="0" width="100%"
                                       height="100%">
                                    <tr>
                                        <td align="center" valign="middle" class="bg_middle"><?php
switch (strGet("function")) {
    case "sync2db":
        if (Sync2DB()) {
            ShowCompareList();
        } else {
            echo "同步失败，可能是数据库操作错误<br /><input type=\"button\" class=\"button1\" value=\"确定\" onclick=\"window.location='toolchecker.php'\"/>";
        }
        break;
    case "sync2cache":
        if (Sync2Cache()) {
            ShowCompareList();
        } else {
            echo "同步失败，可能是因为文件系统权限限制<br /><input type=\"button\" class=\"button1\" value=\"确定\" onclick=\"window.location='toolchecker.php'\"/>";
        }

        break;
    default:
        ShowCompareList();
}
db_close();
?></td>
                                    </tr>
                                </table>
                            </td>
                            <td class="bg_border"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bg_bottom3"></td>
            </tr>
        </table>
    </center>
</body>
</html>
