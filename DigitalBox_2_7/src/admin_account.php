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
require("modules/pages/UserAdminPage.class.php");

$adminpage = new UserAdminPage();

//left
if (strGet("function") == "") {
    switch (strGet("module")) {
        case "changepwd":
            require("modules/boxes/Admin_ChPWD.class.php");
            $adminpage->AddBox(new Admin_ChPWD());
            break;
        case "add":
            require("modules/boxes/Admin_AddUser.class.php");
            $adminpage->AddBox(new Admin_AddUser());
            break;
        default:
            global $_passport;
            if (isset($_passport) && $_passport->isMaster()) {
                require("modules/boxes/Admin_UserList.class.php");
                $adminpage->AddBox(new Admin_UserList());
            } else {
                require("modules/boxes/Admin_ChPWD.class.php");
                $adminpage->AddBox(new Admin_ChPWD());
            }
    }
} else if (strGet("function") == "delete" && strPost("password") == "") {
    $html = TransformTpl("account_delete", array(
        "Account_TargetUID" => strPost("UID")
            ));
    $box = new Box("Left", "box3");
    $box->SetHeight("auto");
    $box->SetTitle("删除人员");
    $box->SetContent($html, "center", "middle", 2);
    $adminpage->AddBox($box);
} else {
    require("modules/data/user_admin.module.php");
    $error = "";
    $info = "";
    $title = "成功";
    $back = "admin_account.php";
    global $_passport;
    $usradmin = new User_Admin($_passport);
    if ($usradmin->check()) {
        switch (strGet("module")) {
            case "changepwd":
                if ($usradmin->changePWD()) {
                    $info = "密码修改成功！";
                } else {
                    $error.="密码修改失败;";
                    $back = "back";
                }
                break;
            case "add":
                if ($usradmin->add()) {
                    $info = "新人员已经加入！";
                } else {
                    $error.="人员添加失败;";
                    $back = "back";
                }
                break;
            case "delete":
                if ($usradmin->delete()) {
                    $info = "删除完毕！";
                } else {
                    $error.="删除失败;";
                }
                break;
        }
    }
    $error.=$usradmin->error;
    if ($error != "") {
        $title = GetLangData("error");
        $info = ErrorList($error);
    }
    $adminpage->AddBox(new MsgBox($info, $title, $back));
}

$adminpage->SetTitle();
$adminpage->Show();
