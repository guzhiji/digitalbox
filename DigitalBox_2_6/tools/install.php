<?php
/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  install.php
  ------------------------------------------------------------------
 */
//-----------------------charset--------------------------------------
define("dbCharset", "utf8");
define("dbCollate", "utf8_general_ci");
//-----------------------data--------------------------------------
$sysfiles = array(
    "admin_account.php",
    "media.php",
    "admin_ads.php",
    "article.php",
    "admin_content.php",
    "picture.php",
    "admin_editor_uploader.php",
    "channel.php",
    "admin_editor_uploadlist.php",
    "checkcode.php",
    "search.php",
    "admin_event.php",
    "software.php",
    "admin_friendsite.php",
    "class.php",
    "style.php",
    "admin.php",
    "admin_recyclebin.php",
    "friendsite.php",
    "admin_script.php",
    "guestbook.php",
    "admin_setting.php",
    "visitorcount.txt",
    "admin_style.php",
    "index.php",
    "vote.php",
    "admin_upload.php",
    "login.php",
    "cache/settings.php",
    "ckeditor/ckeditor.js",
    "modules/common.module.php",
    "modules/config.php",
    "modules/Passport.class.php",
    "modules/data/article_admin.module.php",
    "modules/data/content_admin.module.php",
    "modules/data/sql_content.module.php",
    "modules/data/CacheManager.class.php",
    "modules/data/database.module.php",
    "modules/data/style_admin.module.php",
    "modules/data/channel_admin.module.php",
    "modules/data/friendsite_admin.module.php",
    "modules/data/Uploader.class.php",
    "modules/data/class_admin.module.php",
    "modules/data/media_admin.module.php",
    "modules/data/user_admin.module.php",
    "modules/data/clipboard.module.php",
    "modules/data/picture_admin.module.php",
    "modules/data/vote_admin.module.php",
    "modules/data/comment_admin.module.php",
    "modules/data/setting.module.php",
    "modules/data/comment.module.php",
    "modules/data/software_admin.module.php",
    "modules/view/AdminPage.class.php",
    "modules/view/GridView.class.php",
    "modules/view/PortalPage.class.php",
    "modules/view/Box.class.php",
    "modules/view/GuestBook.class.php",
    "modules/view/SettingAdminPage.class.php",
    "modules/view/channel_admin.module.php",
    "modules/view/ImageList.class.php",
    "modules/view/SiteList.class.php",
    "modules/view/class_admin.module.php",
    "modules/view/ListView.class.php",
    "modules/view/software_editor.module.php",
    "modules/view/CommentList.class.php",
    "modules/view/Navigator.class.php",
    "modules/view/TitleList.class.php",
    "modules/view/content_admin.module.php",
    "modules/view/Page.class.php",
    "modules/view/uploadlist.module.php",
    "modules/view/ContentAdminPage.class.php",
    "modules/view/PagingBar.class.php",
    "modules/view/VoteList.module.php",
    "modules/view/ContentList.class.php",
    "modules/view/PopupPage.class.php",
    "templates/account_add.tpl",
    "templates/media_info.tpl",
    "templates/account_changepwd.tpl",
    "templates/media_rp.tpl",
    "templates/account_delete.tpl",
    "templates/media.tpl",
    "templates/adminpage.tpl",
    "templates/media_wp.tpl",
    "templates/article_editor.tpl",
    "templates/navibutton1.tpl",
    "templates/article.tpl",
    "templates/navilink1.tpl",
    "templates/banner.tpl",
    "templates/pageinfo.tpl",
    "templates/box1.tpl",
    "templates/picture_editor.tpl",
    "templates/box2.tpl",
    "templates/picture_info.tpl",
    "templates/box3.tpl",
    "templates/picture.tpl",
    "templates/calendar.tpl",
    "templates/popuppage.tpl",
    "templates/channel_editor.tpl",
    "templates/portalpage.tpl",
    "templates/channellist_item.tpl",
    "templates/recyclebin_clearconfirm.tpl",
    "templates/channellist.tpl",
    "templates/recyclebin_item.tpl",
    "templates/class_editor.tpl",
    "templates/recyclebin_list.tpl",
    "templates/classlist_btn_move.tpl",
    "templates/scriptlist_container.tpl",
    "templates/classlist_btn.tpl",
    "templates/scriptlist_item.tpl",
    "templates/classlist_item.tpl",
    "templates/search_container.tpl",
    "templates/classlist.tpl",
    "templates/search_item.tpl",
    "templates/code_editor.tpl",
    "templates/setting_ads.tpl",
    "templates/commentlist_admin_item.tpl",
    "templates/setting_basic.tpl",
    "templates/commentlist_admin.tpl",
    "templates/setting_detail.tpl",
    "templates/commentlist_container.tpl",
    "templates/setting_friendsite.tpl",
    "templates/commentlist_item.tpl",
    "templates/setting_home.tpl",
    "templates/content_controlbar_item.tpl",
    "templates/sitelist_container_editor.tpl",
    "templates/content_controlbar.tpl",
    "templates/sitelist_container_small.tpl",
    "templates/contentlist_admin_btn_move.tpl",
    "templates/sitelist_container.tpl",
    "templates/contentlist_admin_btn.tpl",
    "templates/sitelist_empty_editor.tpl",
    "templates/contentlist_admin.tpl",
    "templates/sitelist_empty.tpl",
    "templates/contentlist_morebtn.tpl",
    "templates/sitelist_item_editor.tpl",
    "templates/contentlist_pagination.tpl",
    "templates/sitelist_item_small.tpl",
    "templates/event_addvoteitem.tpl",
    "templates/sitelist_item.tpl",
    "templates/event_home.tpl",
    "templates/software_editor.tpl",
    "templates/event_notice.tpl",
    "templates/software.tpl",
    "templates/event_setvotedesc.tpl",
    "templates/stylelist_edit_item.tpl",
    "templates/footer.tpl",
    "templates/stylelist_edit.tpl",
    "templates/guestbook_controlbox.tpl",
    "templates/stylelist_select_item.tpl",
    "templates/guestbook_editor.tpl",
    "templates/stylelist_select.tpl",
    "templates/guestbook_message.tpl",
    "templates/titlelist_container.tpl",
    "templates/imagelist_item_checkbox.tpl",
    "templates/titlelist_item_checkbox.tpl",
    "templates/imagelist_item_empty.tpl",
    "templates/titlelist_item_empty.tpl",
    "templates/imagelist_item_rest.tpl",
    "templates/titlelist_item.tpl",
    "templates/imagelist_item.tpl",
    "templates/uploadlist_item.tpl",
    "templates/imagelist_row.tpl",
    "templates/uploadlist.tpl",
    "templates/imagelist_table.tpl",
    "templates/upload.tpl",
    "templates/login.tpl",
    "templates/userlist_item.tpl",
    "templates/logobanner.tpl",
    "templates/userlist.tpl",
    "templates/media_editor.tpl",
    "templates/voteform_editor.tpl",
    "templates/media_fp.tpl",
    "templates/voteform_small.tpl",
    "templates/media_if.tpl",
    "templates/voteform.tpl"
);

$systables[0] = "CREATE TABLE IF NOT EXISTS setting_info (";
$systables[0] .= "setting_name char(50) NOT NULL,";
$systables[0] .= "setting_type enum('0','1','2') NOT NULL default '0',";
$systables[0] .= "setting_value char(255) NULL,";
$systables[0] .= "PRIMARY KEY  (setting_name)";
$systables[0] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[1] = "CREATE TABLE IF NOT EXISTS article_info (";
$systables[1] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[1] .= "article_name CHAR(255) NULL,";
$systables[1] .= "parent_class INT(10) NOT NULL,";
$systables[1] .= "article_author CHAR(255) NULL,";
$systables[1] .= "article_time DATETIME NOT NULL,";
$systables[1] .= "article_text TEXT NULL,";
$systables[1] .= "article_HTML ENUM('true','false') NOT NULL DEFAULT 'false',";
$systables[1] .= "visitor_count INT(10) NOT NULL DEFAULT 0,";
$systables[1] .= "PRIMARY KEY (id),";
$systables[1] .= "KEY parent_class (parent_class)";
$systables[1] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[2] = "CREATE TABLE IF NOT EXISTS software_info (";
$systables[2] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[2] .= "software_name CHAR(50) NULL,";
$systables[2] .= "parent_class INT(10),";
$systables[2] .= "software_add CHAR(255) NULL,";
$systables[2] .= "software_producer CHAR(50) NULL,";
$systables[2] .= "software_type CHAR(50) NULL,";
$systables[2] .= "software_language CHAR(50) NULL,";
$systables[2] .= "software_size INT(10) NOT NULL DEFAULT 0,";
$systables[2] .= "software_environment CHAR(50) NULL,";
$systables[2] .= "software_grade INT(1) NOT NULL DEFAULT 2,";
$systables[2] .= "software_text TEXT NULL,";
$systables[2] .= "software_HTML ENUM('true','false') NOT NULL DEFAULT 'false',";
$systables[2] .= "visitor_count INT(10) NOT NULL DEFAULT 0,";
$systables[2] .= "software_time DATETIME,";
$systables[2] .= "PRIMARY KEY (id),";
$systables[2] .= "KEY parent_class (parent_class)";
$systables[2] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[3] = "CREATE TABLE IF NOT EXISTS picture_info (";
$systables[3] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[3] .= "picture_name CHAR(50) NULL,";
$systables[3] .= "parent_class INT(10),";
$systables[3] .= "picture_add CHAR(255) NULL,";
$systables[3] .= "picture_text TEXT NULL,";
$systables[3] .= "picture_HTML ENUM('true','false') NOT NULL DEFAULT 'false',";
$systables[3] .= "visitor_count INT(10) NOT NULL DEFAULT 0,";
$systables[3] .= "picture_time DATETIME,";
$systables[3] .= "PRIMARY KEY (id),";
$systables[3] .= "KEY parent_class (parent_class)";
$systables[3] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[4] = "CREATE TABLE IF NOT EXISTS media_info (";
$systables[4] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[4] .= "media_name CHAR(255) NULL,";
$systables[4] .= "parent_class INT(10),";
$systables[4] .= "media_add CHAR(255) NULL,";
$systables[4] .= "media_text TEXT NULL,";
$systables[4] .= "media_HTML ENUM('true','false') NOT NULL DEFAULT 'false',";
$systables[4] .= "visitor_count INT(10) NOT NULL DEFAULT 0,";
$systables[4] .= "media_time DATETIME,";
$systables[4] .= "PRIMARY KEY (id),";
$systables[4] .= "KEY parent_class (parent_class)";
$systables[4] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[5] = "CREATE TABLE IF NOT EXISTS guest_info (";
$systables[5] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[5] .= "guest_IP CHAR(15),";
$systables[5] .= "guest_date DATETIME,";
$systables[5] .= "guest_name CHAR(50) NULL,";
$systables[5] .= "guest_head INT(2) NOT NULL DEFAULT 1,";
$systables[5] .= "guest_mail CHAR(50) NULL,";
$systables[5] .= "guest_title CHAR(50) NULL,";
$systables[5] .= "guest_homepage CHAR(255) NULL,";
$systables[5] .= "guest_text TEXT,";
$systables[5] .= "PRIMARY KEY (id)";
$systables[5] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[6] = "CREATE TABLE IF NOT EXISTS class_info (";
$systables[6] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[6] .= "class_name CHAR(50) NULL,";
$systables[6] .= "parent_channel INT(10),";
$systables[6] .= "PRIMARY KEY (id),";
$systables[6] .= "KEY parent_channel (parent_channel)";
$systables[6] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[7] = "CREATE TABLE IF NOT EXISTS channel_info (";
$systables[7] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[7] .= "channel_name CHAR(50) NULL,";
$systables[7] .= "channel_add CHAR(255) NULL,";
$systables[7] .= "channel_type INT(1),";
$systables[7] .= "channel_image CHAR(255) NULL,";
$systables[7] .= "PRIMARY KEY (id),";
$systables[7] .= "KEY channel_type (channel_type)";
$systables[7] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[8] = "CREATE TABLE IF NOT EXISTS friendsite_info (";
$systables[8] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[8] .= "site_name CHAR(50) NULL,";
$systables[8] .= "site_add CHAR(255) NULL,";
$systables[8] .= "site_logo CHAR(255) NULL,";
$systables[8] .= "site_text CHAR(100) NULL,";
$systables[8] .= "PRIMARY KEY (id)";
$systables[8] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[9] = "CREATE TABLE IF NOT EXISTS admin_info (";
$systables[9] .= "admin_UID CHAR(50) NOT NULL,";
$systables[9] .= "admin_PWD CHAR(100) NOT NULL,";
$systables[9] .= "PRIMARY KEY (admin_UID)";
$systables[9] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[10] = "CREATE TABLE IF NOT EXISTS style_info (";
$systables[10] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[10] .= "style_name CHAR(50),";
$systables[10] .= "style_imagefolder CHAR(50),";
$systables[10] .= "style_cssfile CHAR(50),";
$systables[10] .= "PRIMARY KEY (id)";
$systables[10] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[11] = "CREATE TABLE IF NOT EXISTS vote_info  (";
$systables[11] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[11] .= "vote_name CHAR(50) NOT NULL,";
$systables[11] .= "vote_value INT(10),";
$systables[11] .= "PRIMARY KEY (id)";
$systables[11] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[12] = "CREATE TABLE IF NOT EXISTS upload_info  (";
$systables[12] .= "upload_filename CHAR(255) NOT NULL,";
$systables[12] .= "upload_filecaption CHAR(255) NOT NULL,";
$systables[12] .= "PRIMARY KEY (upload_filename)";
$systables[12] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

//-----------------------utilities--------------------------------------
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

function toScriptString($str) {
    $str = str_replace("\\", "\\\\", $str);
    $str = str_replace("\"", "\\\"", $str);
    return "\"$str\"";
}

function PrintResult($result) {
    if ($result) {
        echo "……<span style=\"color: #80A1D6\">成功</span><br />";
    } else {
        echo "……<span style=\"color: #FF0000\">失败</span><br />";
    }
}

//-----------------------prepare--------------------------------------
function FuncNotSupported() {
    $funclist = array(
        array("fopen", "文件（档案）系统模块"),
        array("session_start", "Session模块"),
        array("mysql_connect", "MySQL数据库模块")
    );
    $s = array();
    foreach ($funclist as $func) {
        if (!function_exists($func[0]))
            $s[] = $func[1];
    }
    return $s;
}

function IsComplete(&$db2files) {
    $e = TRUE;
    $syspath = GetSystemPath() . "/";
    foreach ($db2files as $db2file) {
        if (!file_exists($syspath . $db2file)) {
            $e = FALSE;
            break;
        }
    }
    return $e;
    /*
      $e=TRUE;
      $DB2Path=$_SERVER["SCRIPT_FILENAME"];
      $DB2Path=substr($DB2Path,0,strlen($DB2Path)-11);
      for($a=0;$a<sizeof($db2files);$a++){
      if(!file_exists($DB2Path.$db2files[$a])){
      $e=FALSE;
      break;
      }
      }
      return $e;
     */
}

function PrepareInstallation(&$files) {
    if (IsComplete($files)) {
        $NotSupported = FuncNotSupported();
        if (count($NotSupported) == 0) {
            ShowWelcome();
        } else {
            echo "<div style=\"text-align: left;\">您的PHP不支持：<ul>";
            foreach ($NotSupported as $nsItem) {
                echo "<li>$nsItem</li>";
            }
            echo "</ul></div>";
        }
    } else {
        echo "DigitalBox系统不完整，可能已经被破坏";
    }
}

function IsDBConfigured() {
    if (!defined("dbMySQLHostName")) {
        require_once("modules/config.php");
    }
    return TestDBConn(dbMySQLHostName, dbMySQLUserName, dbMySQLPassword);
}

function ShowWelcome() {
    echo "<table>";
    echo "<tr><td rowspan=\"2\"><img src=\"images/logo2.gif\"></td><th valign=\"middle\" align=\"center\">欢迎使用 DigitalBox 2.6</th></tr>";
    echo "<tr><td align=\"center\">Copyright &copy 2011 (by GuZhiji Studio)</td></tr>";
    echo "<tr><td align=\"center\" colspan=2>";
    if (IsDBConfigured()) {
        echo "<input type=\"button\" value=\"使用当前数据库设置\" class=\"button1\" onclick=\"window.location.href='?step=3'\">";
        echo " <input type=\"button\" value=\"重设数据库\" class=\"button1\" onclick=\"window.location.href='?step=1'\">";
    } else {
        echo "<input type=\"button\" value=\"下一步\" class=\"button1\" onclick=\"window.location.href='?step=1'\">";
    }
    echo " <input type=\"button\" value=\"返回\" class=\"button1\" onclick=\"window.location.href='toolchecker.php'\"></td></tr>";
    echo "</table>";
}

//-----------------------step1--------------------------------------
function ShowDBConnForm() {
    echo "<form method=\"post\" action=\"?step=2\"><table>";
    echo "<tr><td align=\"right\">数据库主机：</td><td><input type=\"text\" name=\"HostName\" value=\"localhost\"></td></tr>";
    echo "<tr><td align=\"right\">数据库用户：</td><td><input type=\"text\" name=\"UserName\" value=\"root\"></td></tr>";
    echo "<tr><td align=\"right\">数据库密码：</td><td><input type=\"password\" name=\"Password\"></td></tr>";
    echo "<tr><td align=\"right\">数据库名称：</td><td><input type=\"text\" name=\"DBName\" value=\"DigitalBoxV2\"></td></tr>";
    echo "<tr><td align=\"right\">系统前缀：</td><td><input type=\"text\" name=\"Prefix\" value=\"DB2\"></td></tr>";
    echo "<tr><td></td><td>（多个DB2系统不互相干扰时需要改变）</td></tr>";
    echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"下一步\" class=\"button1\"></td></tr>";
    echo "</table></form>";
}

//-----------------------step2--------------------------------------
function SaveConfig($HostName, $UserName, $Password, $DBName, $Prefix) {
    $HostName = str_replace("\"", "\\\"", $HostName);
    $UserName = str_replace("\"", "\\\"", $UserName);
    $Password = str_replace("\"", "\\\"", $Password);
    $DBName = str_replace("\"", "\\\"", $DBName);
    $Prefix = str_replace("\"", "\\\"", $Prefix);
    $cc = "<?php\n";
    $cc .= "//--Database---------------------------------\n";
    $cc .= "define(\"dbMySQLHostName\",\"$HostName\");\n";
    $cc .= "define(\"dbMySQLUserName\",\"$UserName\");\n";
    $cc .= "define(\"dbMySQLPassword\",\"$Password\");\n";
    $cc .= "define(\"dbDatabaseName\",\"$DBName\");\n";
    $cc .= "//--Prefix-----------------------------------\n";
    $cc .= "//如果在您的服务器上使用了多个DigitalBox 2.x，则应使用不同的dbPrefix\n";
    $cc .= "//一般可默认为“DB2”\n";
    $cc .= "define(\"dbPrefix\",\"$Prefix\");\n";
    $cc .= "?>";
    $fp = @fopen(GetSystemPath() . "/modules/config.php", "w");
    if ($fp) {
        fputs($fp, $cc);
        fclose($fp);
        return TRUE;
    }
    return FALSE;
}

function TestDBConn($HostName, $UserName, $Password) {
    $cid = @mysql_connect($HostName, $UserName, $Password);
    if ($cid) {
        mysql_close($cid);
        return TRUE;
    } else {
        return FALSE;
    }
}

function ConfigDatabase() {

    $cuHostName = strPost("HostName");
    $cuUserName = strPost("UserName");
    $cuPassword = strPost("Password");
    $cuDBName = strPost("DBName");
    $cuPrefix = strPost("Prefix");

    $err_tip = "";
    if ($cuHostName == "")
        $err_tip .= "数据库主机不能为空;";
    if ($cuUserName == "")
        $err_tip .= "数据库用户不能为空;";
    if ($cuDBName == "")
        $err_tip .= "数据库名称不能为空;";
    if ($cuPrefix == "")
        $err_tip .= "系统前缀不能为空;";
    if (strlen($cuPrefix) > 10)
        $err_tip .= "系统前缀过长;";

    if (!TestDBConn($cuHostName, $cuUserName, $cuPassword)) {
        $err_tip .= "无法接上此数据库;";
    }

    if ($err_tip != "") {
        echo "<table><tr><td>" . ErrorList($err_tip) . "</td></tr><tr><td align=\"center\"><input type=\"button\" value=\"返回\" class=\"button1\" onclick=\"history.back(1)\"></td></tr></table>";
    } else {
        if (!SaveConfig($cuHostName, $cuUserName, $cuPassword, $cuDBName, $cuPrefix)) {
            echo "<table><tr><td>可能是权限问题，设置信息无法保存，请到 modules/config.php 文件中手工填写，然后重新开始安装，并选择<b>使用当前数据库设置</b></td></tr><tr><td align=\"center\"><input type=\"button\" value=\"确定\" class=\"button1\" onclick=\"window.location.href='?step=0'\"></td></tr></table>";
        } else {
            echo "<table><tr><td align=\"center\">设置信息已经保存在本系统根目录下的 modules/config.php 文件中了。下一步是输入站长信息。</td></tr><tr><td align=\"center\"><input type=\"button\" class=\"button1\" value=\"下一步\" onclick=\"window.location.href='?step=3'\"></td></tr></table>";
        }
    }
}

//-----------------------step3--------------------------------------
function ShowMasterForm() {
    echo "<form method=\"post\" action=\"?step=4\"><table>";
    echo "<tr><td align=\"right\">用户名称：</td><td><input type=\"text\" name=\"Master_UID\"></td></tr>";
    echo "<tr><td align=\"right\">用户密码：</td><td><input type=\"password\" name=\"Master_PWD\"></td></tr>";
    echo "<tr><td align=\"right\">密码确认：</td><td><input type=\"password\" name=\"Master_CheckPWD\"></td></tr>";
    echo "<tr><td align=\"right\">站长邮箱：</td><td><input type=\"text\" name=\"Master_Mail\"></td></tr>";
    echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"下一步\" class=\"button1\"></td></tr>";
    echo "</table></form>";
}

//-----------------------step4--------------------------------------
function GetTableName($sqltext) {
    return trim(substr($sqltext, strlen("CREATE TABLE IF NOT EXISTS "), strpos($sqltext, "(") - strlen("CREATE TABLE IF NOT EXISTS ")));
}

function MakeTables($connid, &$tables) {
    $r = TRUE;
    foreach ($tables as $sql) {
        echo "构建数据表：" . GetTableName($sql);
        $r = !!db_query($connid, $sql);
        PrintResult($r);
    }
    return $r;
}

function ImportSettings($connid) {

    foreach ($_cachedData["settings"] as $skey => $svalue) {
        echo "注入设置信息：" . $skey;
        $notfound = TRUE;
        $rs = db_query($connid, "SELECT setting_name FROM setting_info WHERE setting_name=\"%s\"", array($skey));
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0])) {
                $notfound = FALSE;
            }
            db_free($rs);
        }

        if ($notfound) {
            $type = 0;
            if (!is_string($svalue)) {
                if (is_bool($svalue)) {
                    $type = 2;
                    $svalue = $svalue ? "true" : "false";
                } else {
                    $type = 1;
                    if (is_int($svalue))
                        $svalue = strval($svalue);
                    else
                        $svalue = strval(doubleval($svalue));
                }
            }
            PrintResult(!!db_query($connid, "INSERT INTO setting_info (setting_name,setting_type,setting_value) VALUES (\"%s\",\"%s\",\"%s\")", array($skey, $type, $svalue)));
        } else if ($skey == "master_name" || $skey == "master_mail") {
            PrintResult(!!db_query($connid, "UPDATE setting_info SET setting_value=\"%s\" WHERE setting_name=\"%s\"", array($svalue, $skey)));
        } else {
            PrintResult(TRUE);
        }
    }
}

function SaveMaster($connid, $Master_UID, $Master_PWD) {
    require_once("modules/Passport.class.php");
    $found = FALSE;
    $rs = db_query($connid, "SELECT admin_UID FROM admin_info WHERE admin_UID=\"%s\"", array($Master_UID));
    if ($rs) {
        $list = db_result($rs);
        if (isset($list[0])) {
            $found = TRUE;
        }
        db_free($rs);
    }
    $r = NULL;
    if ($found) {
        echo "修改站长密码";
        $r = db_query($connid, "UPDATE admin_info SET admin_PWD=\"%s\" WHERE admin_UID=\"%s\"", array(Passport::PWDEncrypt($Master_PWD), $Master_UID));
    } else {
        echo "加入站长管理员";
        $r = db_query($connid, "INSERT INTO admin_info (admin_UID,admin_PWD) VALUES (\"%s\",\"%s\")", array($Master_UID, Passport::PWDEncrypt($Master_PWD)));
    }
    PrintResult(!!$r);
}

function validateEMail($email) {
    return!!preg_match("/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$/i", $email);
}

function SetupDatabase(&$tables, &$settings) {

    require_once("modules/data/database.module.php");
    require_once("modules/data/user_admin.module.php");
    $Master_UID = strPost("Master_UID");
    $Master_PWD = strPost("Master_PWD");
    $Master_CheckPWD = strPost("Master_CheckPWD");
    $Master_Mail = strPost("Master_Mail");
    $err_tip = "";
    $err_tip .= User_Admin::UIDCheck($Master_UID);
    $err_tip .= User_Admin::PWDCheck($Master_PWD, $Master_CheckPWD, $Master_UID);

    if (!validateEMail($Master_Mail))
        $err_tip .= "站长邮箱地址错误;";

    echo "<table><tr><td align=\"left\">";
    if ($err_tip == "") {
        require_once("modules/config.php");
        $connid = db_connect();
        if ($connid) {
            if (!mysql_query("CREATE DATABASE IF NOT EXISTS " . dbDatabaseName . " DEFAULT CHARACTER SET " . dbCharset . " COLLATE " . dbCollate . ";", $connid)) {
                echo "数据库建立失败";
            } else {
                echo "准备开始……<br />";

                MakeTables($connid, $tables);

                require_once("modules/data/CacheManager.class.php");
                $cm = new CacheManager("settings");
                $cm->SetValue("master_name", $Master_UID);
                $cm->SetValue("master_mail", $Master_Mail);
                ImportSettings($connid);

                echo "刷新设置信息脚本文件：cache/settings.php";
                PrintResult($cm->Save());

                SaveMaster($connid, $Master_UID, $Master_PWD);

                echo "与styles目录同步风格信息";
                require_once("modules/data/style_admin.module.php");
                PrintResult(SyncStyles($connid));

                echo "完成！";
            }
            db_close($connid);
        } else {
            echo "数据库连接失败";
        }
    } else {
        echo ErrorList($err_tip);
    }
    echo "</td></tr><tr><td align=\"center\">";
    if (strlen($err_tip) > 0) {
        echo "<input type=\"button\" value=\"返回\" class=\"button1\" onclick=\"history.back(1)\">";
    } else {
        echo "<input type=\"button\" value=\"下一步\" class=\"button1\" onclick=\"window.location.href='toolchecker.php'\">";
    }
    echo "</td></tr></table>";
}

//-----------------------presentation--------------------------------------
?>
<html>
    <head>
        <title>系统安装 - 附加工具 - DigitalBox 2.6</title>
        <link rel="stylesheet" href="stylesheets/main.css" />
        <link rel="Shortcut Icon" href="DigitalBoxIcon.ico" />
    </head>
    <body>
    <center>
        <table border="0" cellspacing="0" cellpadding="0" width="580">
            <tr>
                <td class="bg_top3" align="left">&nbsp;&nbsp;系统安装</td>
            </tr>
            <tr>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0" width="100%"
                           height="100%">
                        <tr>
                            <td class="bg_border"></td>
                            <td>
                                <table border="0" cellspacing="0" cellpadding="20" width="100%"
                                       height="100%">
                                    <tr>
                                        <td align="center" valign="middle" class="bg_middle"><?php
if (!isset($_GET["step"])) {
    PrepareInstallation($sysfiles);
} else {
    switch (intval($_GET["step"])) {
        case 1:
            ShowDBConnForm();
            break;
        case 2:
            ConfigDatabase();
            break;
        case 3:
            ShowMasterForm();
            break;
        case 4:
            SetupDatabase($systables, $syssettings);
            break;
        default:
            PrepareInstallation($sysfiles);
    }
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
