<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  admin_account.php
  ------------------------------------------------------------------
 */
require("modules/view/AdminPage.class.php");
require("modules/common.module.php");
require("modules/view/Box.class.php");

function ShowUserList($connid, AdminPage $adminpage) {
    $totalcount = 0;
    $pagesize = GetSettingValue("general_list_maxlen");

    require_once("modules/view/ListView.class.php");
    $userlist = new ListView("userlist_item");

    $rs = db_query($connid, "SELECT count(*) FROM admin_info");
    if ($rs) {
        $list = db_result($rs);
        if (isset($list[0])) {
            $totalcount = $list[0][0];
        }
        db_free($rs);
    }
    require_once("modules/view/PagingBar.class.php");
    $pb = new PagingBar();
    $pb->SetPageCount($totalcount, $pagesize);
    $userlist->SetContainer("userlist", array(
        "Master" => GetSettingValue("master_name"),
        "PagingBar" => $pb->GetHTML()
    ));
    $pagecount = $pb->GetPageCount();
    $pagenumber = $pb->GetPageNumber();
    $start = $pagesize * ($pagenumber - 1);
    $rs = db_query($connid, "SELECT admin_UID FROM admin_info LIMIT $start,$pagesize");
    if ($rs) {
        $list = db_result($rs);
        if ($totalcount > 0) {
            foreach ($list as $item) {
                $userlist->AddItem(array(
                    "UID" => $item["admin_UID"]
                ));
            }
        }
        db_free($rs);
    }
    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("管理人员列表");
    $box->SetContent($userlist->GetHTML(), "center", "middle", 2);
    $adminpage->AddToLeft($box);
}

function ShowChangePWDForm(AdminPage $adminpage) {
    $html = TransformTpl("account_changepwd", array(
        "Account_Username" => strSession("Admin_UID")
            ));
    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("修改密码");
    $box->SetContent($html, "center", "middle", 2);
    $adminpage->AddToLeft($box);
}

function ShowAddUserForm(AdminPage $adminpage) {
    $html = GetTemplate("account_add");
    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("添加人员");
    $box->SetContent($html, "center", "middle", 2);
    $adminpage->AddToLeft($box);
}

$adminpage = new AdminPage();
$connid = $adminpage->GetDBConn();
$passport = $adminpage->GetPassport();

$menu = array(
    "修改密码" => array("admin_account.php?module=changepwd", FALSE),
    "添加人员" => array("admin_account.php?module=add", TRUE),
    "删除人员" => array("admin_account.php?module=delete", TRUE)
);

//left
if (strGet("function") == "") {
    switch (strGet("module")) {
        case "changepwd":
            ShowChangePWDForm($adminpage);
            break;
        case "add":
            ShowAddUserForm($adminpage);
            break;
        default:
            if ($passport->isMaster())
                ShowUserList($connid, $adminpage);
            else
                ShowChangePWDForm($adminpage);
    }
} else if (strGet("function") == "delete" && strPost("password") == "") {
    $html = TransformTpl("account_delete", array(
        "Account_TargetUID" => strPost("UID")
            ));
    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("删除人员");
    $box->SetContent($html, "center", "middle", 2);
    $adminpage->AddToLeft($box);
} else {
    require("modules/data/user_admin.module.php");
    $error = "";
    $info = "";
    $title = "成功";
    $back = "admin_account.php";
    $usradmin = new User_Admin($connid, $passport);
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
        $title = "错误";
        $info = ErrorList($error);
    }
    $adminpage->ShowInfo($info, $title, $back);
}
//right
$adminpage->ShowNavBox2("账户管理", $menu);
$adminpage->ShowNavBox1();

$adminpage->SetTitle("账户管理");
$adminpage->Show();

$adminpage->CloseDBConn();
?>