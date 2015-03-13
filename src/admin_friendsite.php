<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  admin_friendsite.php
  ------------------------------------------------------------------
 */

function ShowFriendSiteModule(&$connid, AdminPage &$adminpage) {
    $totalcount = 0;
    $pagesize = GetSettingValue("window3_title_maxnum");
    $rs = db_query($connid, "SELECT count(*) FROM friendsite_info");
    if ($rs) {
        $list = db_result($rs);
        if (isset($list[0])) {
            $totalcount = $list[0][0];
        }
        db_free($rs);
    }
    $adminpage->AddJS("function isselected(theForm){for (var i=0; i<theForm.elements.length; i++){var e = theForm.elements[i];if (e.checked) return true; }return false;}");
    $adminpage->AddJS("function add_site(){window.location=\"?module=friendsite&function=add\";}");
    $adminpage->AddJS("function amend_site(){var fsform=document.admin_friendsite;var addr=\"\";for(var a=0;a<fsform.elements.length;a++){var e=fsform.elements[a];if(e.type==\"radio\"&&e.checked){addr=\"admin_friendsite.php?function=save&id=\"+e.value;break;}}if(addr==\"\"){window.alert(\"您未选择对象！\");}else{window.location=addr;}}");
    $adminpage->AddJS("function delete_site(){if (isselected(document.admin_friendsite)){if (window.confirm(\"您真的要删除此友情链接吗？\")){document.admin_friendsite.method=\"post\";document.admin_friendsite.action=\"admin_friendsite.php?function=delete\";document.admin_friendsite.submit();}}else window.alert(\"您未选择对象！\");}");

    require_once("modules/view/SiteList.class.php");
    $sl = new SiteList("sitelist_item_editor", "sitelist_empty_editor");
    $sl->SetContainer("sitelist_container_editor", 2);
    $sl->Bind($connid);

    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("友情链接");
    $box->SetContent($sl->GetHTML(), "center", "middle", 2);
    $adminpage->AddToLeft($box);
}

require("modules/view/SettingAdminPage.class.php");
require("modules/view/Box.class.php");
require("modules/common.module.php");

$adminpage = new SettingAdminPage();
$connid = $adminpage->GetDBConn();
$passport = $adminpage->GetPassport();

if (strGet("function") == "") {
    ShowFriendSiteModule($connid, $adminpage);
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once("modules/data/friendsite_admin.module.php");
        $funcname = "";
        $fsa = new FriendSite_Admin($connid, $passport);
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
            $adminpage->ShowInfo($funcname . "完毕", "成功", "admin_friendsite.php");
        } else {
            $adminpage->ShowInfo(ErrorList($fsa->error), "错误", "back");
        }
    } else {
        $data = NULL;
        switch (strGet("function")) {
            case "save":
                $rs = db_query($connid, "SELECT * FROM friendsite_info WHERE id=%d", array(strGet("id")));
                if ($rs) {
                    $list = db_result($rs);
                    if (isset($list[0])) {
                        $data = array(
                            "Setting_FSite_Function" => "save",
                            "Setting_FSite_Name" => TextForInputBox($list[0]["site_name"]),
                            "Setting_FSite_Add" => TextForInputBox($list[0]["site_add"]),
                            "Setting_FSite_Logo" => TextForInputBox($list[0]["site_logo"]),
                            "Setting_FSite_Text" => TextForInputBox($list[0]["site_text"]),
                            "Setting_FSite_ID" => $list[0]["id"]
                        );
                    }
                }

                break;
            default:
                $data = array(
                    "Setting_FSite_Function" => "add",
                    "Setting_FSite_Name" => "",
                    "Setting_FSite_Add" => "",
                    "Setting_FSite_Logo" => "",
                    "Setting_FSite_Text" => "",
                    "Setting_FSite_ID" => ""
                );
                break;
        }
        if ($data != NULL) {
            $html = TransformTpl("setting_friendsite", $data);
            $box = new Box(3);
            $box->SetHeight("auto");
            $box->SetTitle("友情链接");
            $box->SetContent($html, "center", "middle", 2);
            $adminpage->AddToLeft($box);
        } else {
            $adminpage->ShowInfo("找不到此链接！", "错误", "back");
        }
    }
}

//right
$adminpage->ShowNavBox2();
$adminpage->ShowNavBox1();

$adminpage->SetTitle("友情链接");
$adminpage->Show();

$adminpage->CloseDBConn();
?>
