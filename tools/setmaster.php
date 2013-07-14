<?php
/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require("modules/common.module.php");
require("modules/Passport.class.php");
require("modules/data/user_admin.module.php");
require("modules/cache/PHPCacheEditor.class.php");
$err_tip = "";
if (strtolower(strGet("command")) == "update") {

    $Master_UID = strPost("Master_UID");
    $Master_PWD = strPost("Master_PWD");
    $Master_CheckPWD = strPost("Master_CheckPWD");

    $err_tip .= User_Admin::UIDCheck($Master_UID);
    $err_tip .= User_Admin::PWDCheck($Master_PWD, $Master_CheckPWD, $Master_UID);

    if (strlen($err_tip) == 0) {
        $connid = db_connect();
        if (!$connid) {
            $err_tip .= "数据库连接失败;";
        } else {
            $cm = new PHPCacheEditor("cache", "settings");
            $cm->SetValue("master_name", $Master_UID);
            try {
                $cm->Save();
                if (db_query("UPDATE setting_info SET setting_value=\"%s\" WHERE setting_name='master_name'", array($Master_UID))) {
                    $rs = db_query("SELECT admin_UID FROM admin_info WHERE admin_UID=\"%s\"", array($Master_UID));
                    if ($rs) {
                        $list = db_result($rs);
                        db_free($rs);
                        if (isset($list[0])) {
                            if (!db_query("UPDATE admin_info SET admin_PWD=\"%s\" WHERE admin_UID=\"%s\"", array(Passport::PWDEncrypt($Master_PWD), $Master_UID))) {
                                $err_tip .= "修改站长密码失败;";
                            }
                        } else if (!db_query("INSERT INTO admin_info (admin_UID,admin_PWD) VALUES (\"%s\",\"%s\")", array($Master_UID, Passport::PWDEncrypt($Master_PWD)))) {
                            $err_tip .= "加入站长管理员失败;";
                        }
                    }
                } else {
                    $err_tip .= "修改站长失败;";
                }
            } catch (Exception $ex) {
                $err_tip .= "修改站长失败;";
            }
            db_close();
        }
    }
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>更改站长 - 附加工具 - DigitalBox <?php echo dbVersion; ?></title>
        <link rel="stylesheet" href="stylesheets/main.css" />
        <link rel="Shortcut Icon" href="DigitalBoxIcon.ico" />
    </head>
    <body>
    <center>
        <table border=0 cellspacing=0 cellpadding=0 width=580>
            <tr>
                <td class="bg_top3" align="left">&nbsp;&nbsp;更改站长 - 附加工具</td>
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
if (strtolower(strGet("command")) == "update") {
    if ($err_tip == "") {
        echo "<table><tr><td>站长信息更改成功</td></tr><tr><td align=\"center\"><input type=\"button\" value=\"确定\" class=\"button1\" onclick=\"window.location.href='toolchecker.php'\"></td></tr></table>";
    } else {
        echo "<table><tr><td>" . ErrorList($err_tip) . "</td></tr><tr><td align=\"center\"><input type=\"button\" value=\"返回\" class=\"button1\" onclick=\"history.back(1)\"></td></tr></table>";
    }
} else {
    echo "<form method=\"post\" action=\"setmaster.php?command=update\"><table>";
    echo "<tr><td align=\"right\">站长名称：</td><td><input type=\"text\" name=\"Master_UID\"></td></tr>";
    echo "<tr><td align=\"right\">站长密码：</td><td><input type=\"password\" name=\"Master_PWD\"></td></tr>";
    echo "<tr><td align=\"right\">密码确认：</td><td><input type=\"password\" name=\"Master_CheckPWD\"></td></tr>";
    echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"确定\" class=\"button1\"> <input type=\"button\" value=\"返回\" class=\"button1\" onclick=\"window.location.href='toolchecker.php'\"></td></tr>";
    echo "</table></form>";
}
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
