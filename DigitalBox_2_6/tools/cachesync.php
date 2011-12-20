<?php
/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  cachesync.php
  ------------------------------------------------------------------
 */
require("modules/common.module.php");
require("modules/data/database.module.php");

function ShowCompareList($connid) {
    $message = "数据库连接错误";
    $rs = db_query($connid, "SELECT * FROM setting_info");
    if ($rs) {
        $nonempty = FALSE;
        $list = db_result($rs);
        $html = "<table><tr><th>setting</th><th width=\"100\">database</th><th width=\"100\">cache</th></tr>";
        foreach ($list as $item) {
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
        db_free($rs);
        if ($nonempty) {
            echo $html;
            echo "<tr><td></td>";
            echo "<td><input type=\"button\" class=\"button1\" value=\"to cache &gt;\" onclick=\"window.location='?function=sync2cache'\" /></td>";
            echo "<td><input type=\"button\" class=\"button1\" value=\"&lt; to database\" onclick=\"window.location='?function=sync2db'\" /></td>";
            echo "</tr></table>";
        } else {
            $message = "所有数据经过比较都已经一致";
        }
    }
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
