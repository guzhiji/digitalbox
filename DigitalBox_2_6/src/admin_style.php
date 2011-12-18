<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  admin_style.php
  ------------------------------------------------------------------
 */

function ShowStyleModule(&$connid, AdminPage &$adminpage) {
    $totalcount = 0;
    $pagesize = 10;
    $defaultstyle = "";
    $defaultstyleid = GetSettingValue("default_style");
    $defaultstyletext = "未设置默认风格";
    $rs = db_query($connid, "SELECT count(*) FROM style_info");
    if ($rs) {
        $list = db_result($rs);
        if (isset($list[0])) {
            $totalcount = $list[0][0];
        }
        db_free($rs);
    }
    require_once("modules/view/ListView.class.php");
    require_once("modules/view/PagingBar.class.php");
    $stylelist = new ListView("stylelist_edit_item");
    $pb = new PagingBar();
    $pb->SetPageCount($totalcount, $pagesize);
    $pagecount = $pb->GetPageCount();
    $pagenumber = $pb->GetPageNumber();
    if ($totalcount > 0) {
        $start = $pagesize * ($pagenumber - 1);
        $rs = db_query($connid, "SELECT * FROM style_info LIMIT $start,$pagesize");
        if ($rs) {
            $list = db_result($rs);
            foreach ($list as $item) {
                $stylelist->AddItem(array(
                    "ID" => $item["id"],
                    "Name" => $item["style_name"]
                ));
                if ($item["id"] == $defaultstyleid)
                    $defaultstyle = $item["style_name"];
            }
        }
        db_free($rs);

        if ($defaultstyle != "") {
            $defaultstyletext = "现使用的默认风格为：$defaultstyle";
        }
        $stylelist->SetContainer("stylelist_edit", array(
            "Default" => $defaultstyletext,
            "PagingBar" => $pb->GetHTML()
        ));
    } else {
        $stylelist->SetContainer("stylelist_edit", array(
            "Default" => $defaultstyletext,
            "PagingBar" => $pb->GetHTML()
        ));
    }
    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("风格设置");
    $box->SetContent($stylelist->GetHTML(), "center", "middle", 20);
    $adminpage->AddToLeft($box);
}

require("modules/view/SettingAdminPage.class.php");
require("modules/view/Box.class.php");
require("modules/common.module.php");

$adminpage = new SettingAdminPage();
$connid = $adminpage->GetDBConn();
$passport = $adminpage->GetPassport();

require_once("modules/data/style_admin.module.php");
switch (strGet("function")) {
    case "sync":
        SyncStyles($connid);
        ShowStyleModule($connid, $adminpage);
        break;
    case "setdefault":
        $e = SetDefaultStyle($connid, strPost("id"));
        if ($e) {
            $adminpage->ShowInfo("默认风格设置成功！", "完 成", "admin_style.php");
        } else {
            $adminpage->ShowInfo("默认风格设置失败", "错 误", "admin_style.php");
        }
        break;
    default:
        ShowStyleModule($connid, $adminpage);
}

//right
$adminpage->ShowNavBox2();
$adminpage->ShowNavBox1();

$adminpage->SetTitle("风格");
$adminpage->Show();

$adminpage->CloseDBConn();
?>
