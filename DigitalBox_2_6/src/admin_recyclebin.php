<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  admin_upload.php
  ------------------------------------------------------------------
 */

function ShowRecycledList($type, &$connid, AdminPage &$adminpage) {
    $types = array("文章", "图片", "媒体", "软件");

    $html = "";
    for ($i = 1; $i < 5; $i++) {
        if ($i != $type) {
            $html.="<option value=\"{$i}\">{$types[$i - 1]}</option>";
        }
    }

    $type_cn = GetTypeName($type, 0);
    $type_en = GetTypeName($type, 1);

    $totalcount = 0;
    $pagesize = GetSettingValue("general_list_maxlen");
    $rs = db_query($connid, "SELECT count(*) FROM " . GetTypeName($type, 1) . "_info WHERE parent_class<1");
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
    $pagecount = $pb->GetPageCount();
    $pagenumber = $pb->GetPageNumber();

    require_once("modules/view/ListView.class.php");
    $rl = new ListView("recyclebin_item");
    $rl->SetContainer("recyclebin_list", array(
        "Types" => $html,
        "PagingBar" => $pb->GetHTML(),
        "Type_cn" => $type_cn
    ));

    if ($totalcount > 0) {

        $start = $pagesize * ($pagenumber - 1);
        $rs = db_query($connid, "SELECT * FROM {$type_en}_info WHERE parent_class<1 LIMIT {$start},{$pagesize}");
        if ($rs) {
            $list = db_result($rs);
            foreach ($list as $item) {

                $rl->AddItem(array(
                    "ID" => $item["id"],
                    "Name" => $item["{$type_en}_name"]
                ));
            }
            db_free($rs);
        }
    }

    $adminpage->AddJS("function isselected(theForm){for (var i=0; i<theForm.elements.length; i++){var e = theForm.elements[i];if (e.checked) return true; }return false;}");
    $adminpage->AddJS("function change_mode(){document.admin_recyclebin.method=\"post\";document.admin_recyclebin.action=\"admin_recyclebin.php\";document.admin_recyclebin.submit();}");
    $adminpage->AddJS("function restore_recycled(){if (isselected(document.admin_recyclebin)){if (window.confirm(\"您真的要还原此{$type_cn}吗？\")){document.admin_recyclebin.method=\"post\";document.admin_recyclebin.action=\"admin_recyclebin.php?type={$type}&function=restore\";document.admin_recyclebin.submit();}}else window.alert(\"您未选择对象！\");}");
    $adminpage->AddJS("function delete_recycled(){if (isselected(document.admin_recyclebin)){if (window.confirm(\"删除后不可恢复，您真的要删除此{$type_cn}吗？\")){document.admin_recyclebin.method=\"post\";document.admin_recyclebin.action=\"admin_content.php?module=content&class=0&type={$type_en}&function=delete\";document.admin_recyclebin.submit();}}else window.alert(\"您未选择对象！\");}");
    $adminpage->AddJS("function clear_recycled(){if (window.confirm(\"清空后不可恢复，您真的要清空回收站中的{$type_cn}吗？\")) window.location=\"admin_recyclebin.php?type={$type}&function=clear\";}");

    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("回收站管理（{$type_cn}）");
    $box->SetContent($rl->GetHTML(), "center", "middle", 30);
    $adminpage->AddToLeft($box);
}

function ShowClearConfirmation($type, AdminPage &$adminpage) {
    $type_cn = GetTypeName($type, 0);

    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("清空回收站");
    $box->SetContent(TransformTpl("recyclebin_clearconfirm", array(
                "type" => $type,
                "type_cn" => $type_cn
            )), "center", "middle", 30);
    $adminpage->AddToLeft($box);
}

require("modules/view/ContentAdminPage.class.php");
require("modules/common.module.php");
require("modules/view/Box.class.php");
require("modules/data/clipboard.module.php");

$adminpage = new ContentAdminPage();
$connid = $adminpage->GetDBConn();
$passport = $adminpage->GetPassport();

$type = strGet("type");
if ($type == "")
    $type = strPost("type");
$type = intval($type);
if ($type > 4 || $type < 1)
    $type = 1;

switch (strGet("function")) {
    case "restore":
        $clipboard = new ClipBoard();
        $clipboard->cut("content", array(strPost("id"), GetTypeName($type, 1), "recycled"));
        $adminpage->ShowInfo("点击“确定”选择目标栏目", "准备还原", "admin_content.php?module=content");
        break;
    case "clear":
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            db_query($connid, "DELETE FROM " . GetTypeName($type, 1) . "_info WHERE parent_class<1");
            $adminpage->ShowInfo("回收站中" . GetTypeName($type, 0) . "已被清空", "完成", "admin_recyclebin.php?type=" . $type);
        } else {
            ShowClearConfirmation($type, $adminpage);
        }
        break;
    default:
        ShowRecycledList($type, $connid, $adminpage);
}

//right
$adminpage->ShowNavBox2();
$adminpage->ShowNavBox1();

$adminpage->SetTitle("回收站");
$adminpage->Show();

$adminpage->CloseDBConn();
?>
