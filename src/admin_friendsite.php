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
require("modules/pages/SettingAdminPage.class.php");

$adminpage = new SettingAdminPage();

if (strGet("function") == "") {
    require("modules/boxes/Admin_FriendSiteList.class.php");
    $adminpage->AddBox(new Admin_FriendSiteList());
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once("modules/data/friendsite_admin.module.php");
        $funcname = "";
        global $_passport;
        $fsa = new FriendSite_Admin($_passport);
        if ($fsa->check()) {
            switch (strGet("function")) {
                case "add":
                    $fsa->add();
                    $funcname = "添加";
                    break;
                case "save":
                    $fsa->save();
                    $funcname = "保存";
                    break;
                case "delete":
                    $fsa->delete();
                    $funcname = "删除";
                    break;
            }
        }
        if ($fsa->error == "") {
            UpdateVersion("version_friendsites");
            $adminpage->AddBox(new MsgBox($funcname . "完毕", "成功", "admin_friendsite.php"));
        } else {
            $adminpage->AddBox(new MsgBox(ErrorList($fsa->error), GetLangData("error"), "back"));
        }
    } else {
        require("modules/boxes/Admin_FriendSiteEditor.class.php");
        $adminpage->AddBox(new Admin_FriendSiteEditor());
    }
}

$adminpage->SetTitle("友情链接");
$adminpage->Show();
