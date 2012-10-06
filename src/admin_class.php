<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require("modules/common.module.php");
require("modules/pages/ContentAdminPage.class.php");
require("modules/data/clipboard.module.php");

global $_channelID;
$_channelID = intval(strGet("channel"));

$adminpage = new ContentAdminPage();

$adminpage->SetTitle(GetLangData("classadmin"));

if (strGet("function") == "edit") {
    if (intval(strGet("id")) > 0) {
        global $_classID;
        $_classID = intval(strGet("id"));
    }
    if (intval(strGet("channel")) > 0) {
        global $_channelID;
        $_channelID = intval(strGet("channel"));
    }
    require("modules/boxes/Admin_ClassEditor.class.php");
    $adminpage->AddBox(new Admin_ClassEditor());
} else if (strGet("function") == "cancelmove") {
    $clipboard = new ClipBoard();
    $clipboard->cancel("content");
    require("modules/boxes/Admin_ClassList.class.php");
    $adminpage->AddBox(new Admin_ClassList());
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $_passport;
    require("modules/data/class_admin.module.php");
    $clsadmin = new Class_Admin($_passport);
    $cn = "";
    if ($clsadmin->check()) {
        switch (strGet("function")) {
            case "add":
                $cn = "已经添加到 {$clsadmin->channel_name}";
                $clsadmin->add();
                break;
            case "save":
                $cn = "已经保存";
                $clsadmin->save();
                break;
            case "delete":
                $cn = "已经删除";
                $clsadmin->delete();
                break;
        }
    }
    if ($clsadmin->error != "") {
        $adminpage->AddBox(new MsgBox(ErrorList($clsadmin->error), GetLangData("error"), "back"));
    } else {
        UpdateVersion("version_classes");
        $adminpage->AddBox(new MsgBox($cn, "完成", "?module=content&channel={$_channelID}"));
    }
} else {
    require("modules/boxes/Admin_ClassList.class.php");
    $adminpage->AddBox(new Admin_ClassList());
}

$adminpage->Show();
