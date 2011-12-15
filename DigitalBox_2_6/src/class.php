<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  class.php
  ------------------------------------------------------------------
 */
require("modules/view/PortalPage.class.php");
require("modules/view/Box.class.php");
require("modules/view/ContentList.class.php");
require("modules/common.module.php");

$portalpage = new PortalPage();
$connid = $portalpage->GetDBConn();

$id = trim(strGet("id"));
$type = GetTypeNumber(trim(strGet("type")));

if ($type < 1) {
    $portalpage->RaiseError();
} else {
    $rs = db_query($connid, "select class_info.class_name,channel_info.id as channel_id,channel_info.channel_name from class_info,channel_info where channel_info.channel_type=%d and class_info.parent_channel=channel_info.id and class_info.id=%d", array($type, $id));
    if (!$rs) {
        $portalpage->RaiseError();
    } else {
        $list = db_result($rs);
        if (isset($list[0])) {

            $item = $list[0];

            $portalpage->SetChannel($item["channel_id"], $item["channel_name"]);
            $portalpage->SetClass($id, $item["class_name"]);

            $portalpage->AddKeywords($item["class_name"]);
            $portalpage->AddKeywords($item["channel_name"]);
        } else {
            $portalpage->RaiseError();
        }
        db_free($rs);
    }

    //left

    $title_maxlen = GetSettingValue("box3_title_maxlen");
    $title_maxnum = GetSettingValue("toplist_maxlen");
    $image_maxrow = GetSettingValue("index_grid_maxrow");
    $image_maxcol = 5;

    $contentlist = new ContentList($connid);
    $contentlist->SetChannel($portalpage->_channel_id, $portalpage->_channel_name, $type);
    $contentlist->SetTitleList($title_maxnum, $title_maxlen, TRUE, FALSE, TRUE, 1, FALSE, 2);
    $contentlist->SetImageList($image_maxrow, $image_maxcol, 1);

    $box = new Box(3);
    $box->SetTitle("最热" . GetTypeName($type, 0) . " - " . $portalpage->_channel_name);
    $box->SetContent($contentlist->GetHTML(2, 0), "left", "middle", 10);

    $portalpage->AddToLeft($box);

    $portalpage->AddAdsBox(GetSettingValue("ad_1"), TRUE);

    if (!$portalpage->HasError()) {

        $title_maxnum = GetSettingValue("general_list_maxlen");
        $image_maxrow = GetSettingValue("general_grid_maxrow");

        $contentlist = new ContentList($connid);
        $contentlist->SetTitleList($title_maxnum, $title_maxlen, TRUE, FALSE, FALSE, 1);
        $contentlist->SetImageList($image_maxrow, $image_maxcol, 1);
        $contentlist->SetClass($portalpage->_class_id, $portalpage->_class_name, $type);
        $box = new Box(3);
        $box->SetTitle($portalpage->_class_name);
        $box->SetContent($contentlist->GetHTML(1, 2), "left", "top", 10);
        $portalpage->AddToLeft($box);
    }
    $portalpage->ShowGuestBookBox(GetTypeName($type, 0));

    $portalpage->AddAdsBox(GetSettingValue("ad_2"), TRUE);
}
if ($portalpage->HasError()) {
    $portalpage->ShowInfo("没有可显示的内容", "错误");
}

//right
$portalpage->ShowNoticeBoard();

if ($type > 0) {
    $portalpage->ShowSearchBox("站内搜索", $type);
    $portalpage->ShowNavBox2($type);
}

$portalpage->AddAdsBox(GetSettingValue("ad_3"), FALSE);

$portalpage->ShowCalendarBox();
$portalpage->ShowFriendListBox();

$portalpage->AddAdsBox(GetSettingValue("ad_4"), FALSE);

$portalpage->SetTitle();
$portalpage->Show();

$portalpage->CloseDBConn();
?>
