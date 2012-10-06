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

$adminpage = new ContentAdminPage();

$adminpage->SetTitle(GetLangData("channeladmin"));

if (strGet("function") == "edit") {
    if (intval(strGet("id")) > 0) {
        global $_channelID;
        $_channelID = intval(strGet("id"));
    }
    require("modules/boxes/Admin_ChannelEditor.class.php");
    $adminpage->AddBox(new Admin_ChannelEditor());
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $_passport;
    require("modules/data/channel_admin.module.php");
    $chadmin = new Channel_Admin($_passport);
    $cn = "";
    if ($chadmin->check()) {
        switch (strGet("function")) {
            case "add":
                $cn = "已经添加";
                $chadmin->add();
                break;
            case "save":
                $cn = "已经保存";
                $chadmin->save();
                break;
            case "delete":
                $cn = "已经删除";
                $chadmin->delete();
                break;
        }
    }
    if ($chadmin->error != "") {
        $adminpage->AddBox(new MsgBox(ErrorList($chadmin->error), GetLangData("error"), "back"));
    } else {
        UpdateVersion("version_channels");
        $adminpage->AddBox(new MsgBox($cn, "完成", "?module=content"));
    }
} else {
    require("modules/boxes/Admin_ChannelList.class.php");
    $adminpage->AddBox(new Admin_ChannelList());
}

$adminpage->Show();
