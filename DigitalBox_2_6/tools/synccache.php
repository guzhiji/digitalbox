<?php
/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  synccache.php
  ------------------------------------------------------------------
 */
require("modules/common.module.php");
require("modules/data/database.module.php");

function ShowCompareList($connid) {
    //prepare an error message
    $message = "数据库连接错误";
    $rs = db_query($connid, "SELECT * FROM setting_info");
    if ($rs) {
        $nonempty = FALSE;
        $list = db_result($rs);//fetch settings from db
        db_free($rs);
        $dbs = array();//prepare a hash table for the 2nd loop
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
        require("cache/settings.php");
        if (isset($_cachedData) && isset($_cachedData["settings"])) {
            //2nd loop: based on cached settings
            foreach ($_cachedData["settings"] as $key => $value) {
                //check if not found in the settings from db
                if (!isset($dbs[$key])) {
                    $nonempty = TRUE;
                    if (is_bool($value))
                        $value = $value ? "true" : "false";
                    $html.= "<tr><th align=\"left\">{$key}</th><td></td><td>{$value}</td></tr>";
                }
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

function Sync2Cache($connid) {
    require("modules/data/CacheManager.class.php");
    $cm = new CacheManager("settings");
    $rs = db_query($connid, "SELECT * FROM setting_info");
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
        return $cm->Save();
    }
    return FALSE;
}

function Sync2DB($connid) {
    require("cache/settings.php");
    if (!isset($_cachedData))
        return FALSE;
    if (!isset($_cachedData["settings"]))
        return FALSE;
    $e = FALSE;
    foreach ($_cachedData["settings"] as $key => $value) {
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
        if (!db_query($connid, "UPDATE setting_info SET setting_value=\"%s\",setting_type=\"%d\" WHERE setting_name=\"%s\"", array($value, $type, $key))) {
            $e = TRUE;
        }
        if (mysql_affected_rows($connid) < 1) {
            $rs = db_query($connid, "SELECT \"true\" FROM setting_info WHERE setting_name=\"%s\"", array($key));
            if ($rs) {
                $list = db_result($rs);
                if (!isset($list[0])) {
                    if (!db_query($connid, "INSERT INTO setting_info (setting_name,setting_type,setting_value) VALUES (\"%s\",\"%d\",\"%s\")", array($key, $type, $value))) {
                        $e = TRUE;
                    }
                }
                db_free($rs);
            } else {
                $e = TRUE;
            }
        }
    }
    return!$e;
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
$connid = db_connect();
switch (strGet("function")) {
    case "sync2db":
        if (Sync2DB($connid)) {
            ShowCompareList($connid);
        } else {
            echo "同步失败，可能是数据库操作错误<br /><input type=\"button\" class=\"button1\" value=\"确定\" onclick=\"window.location='toolchecker.php'\"/>";
        }
        break;
    case "sync2cache":
        if (Sync2Cache($connid)) {
            ShowCompareList($connid);
        } else {
            echo "同步失败，可能是因为文件系统权限限制<br /><input type=\"button\" class=\"button1\" value=\"确定\" onclick=\"window.location='toolchecker.php'\"/>";
        }

        break;
    default:
        ShowCompareList($connid);
}
db_close($connid);
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
