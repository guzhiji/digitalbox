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

function ShowUserList(&$connid, AdminPage &$adminpage) {
    $totalcount = 0;
    $pagesize = GetSettingValue("general_list_maxlen");

    $adminpage->AddJS("function isselected(theForm){for (var i=0; i<theForm.elements.length; i++){var e = theForm.elements[i];if (e.checked) return true; }return false;}");
    $adminpage->AddJS("function add_user(){window.location=\"admin_account.php?module=add\";}");
    $adminpage->AddJS("function delete_user(){if (isselected(document.admin_user)){if (window.confirm(\"您真的要删除此管理员吗？\")){document.admin_user.method=\"post\";document.admin_user.action=\"admin_account.php?module=delete&function=delete\";document.admin_user.submit();}}else window.alert(\"您未选择对象！\");}");

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
            $html = TransformTpl("account_changepwd", array(
                "Account_Username" => strSession("Admin_UID")
                    ));
            $box = new Box(3);
            $box->SetHeight("auto");
            $box->SetTitle("修改密码");
            $box->SetContent($html, "center", "middle", 2);
            $adminpage->AddToLeft($box);

            break;
        case "add":
            $html = GetTemplate("account_add");
            $box = new Box(3);
            $box->SetHeight("auto");
            $box->SetTitle("添加人员");
            $box->SetContent($html, "center", "middle", 2);
            $adminpage->AddToLeft($box);
            break;
        default:
            ShowUserList($connid, $adminpage);
    }
} else if (strGet("function") == "delete" && strPost("password") == "") {
    $html = TransformTpl("account_delete", array(
        "Account_UID" => strPost("UID")
            ));
    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("删除人员");
    $box->SetContent($html, "center", "middle", 2);
    $adminpage->AddToLeft($box);
} else {
    require("modules/data/user_admin.module.php");
    $error = "";
    $usradmin = new User_Admin($connid, $passport);
    if ($usradmin->check()) {
        switch (strGet("module")) {
            case "changepwd":
                if ($usradmin->changePWD()) {
                    $adminpage->ShowInfo("密码修改成功！", "成功", "admin_account.php");
                } else {
                    $error.="密码修改失败;";
                }
                break;
            case "add":
                if ($usradmin->add()) {
                    $adminpage->ShowInfo("新人员已经加入！", "成功", "admin_account.php");
                } else {
                    $error.="密码修改失败;";
                }
                break;
            case "delete":
                if ($usradmin->delete()) {
                    $adminpage->ShowInfo("删除完毕！", "成功", "admin_account.php");
                } else {
                    $error.="密码修改失败;";
                }
                break;
        }
    }
    $error.=$usradmin->error;
    if ($error != "") {
        $adminpage->ShowInfo(ErrorList($error), "错误", "back");
    }
}
//right
$adminpage->ShowNavBox2("账户管理", $menu);
$adminpage->ShowNavBox1();

$adminpage->SetTitle("账户管理");
$adminpage->Show();

$adminpage->CloseDBConn();
?>