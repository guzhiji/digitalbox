<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  channel.php
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
    $rs = db_query($connid, "select channel_name from channel_info where channel_type=$type and id=%d", array($id));
    if (!$rs) {
        $portalpage->RaiseError();
    } else {
        $list = db_result($rs);
        if (isset($list[0])) {

            $item = $list[0];
            $portalpage->SetChannel($id, $item["channel_name"]);

            $portalpage->AddKeywords($item["channel_name"]);
        } else {
            $portalpage->RaiseError();
        }
        db_free($rs);
    }

    //left
    //hottest
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

    //classes
    if (!$portalpage->HasError()) {
        $empty = TRUE;
        $title_maxlen = GetSettingValue("box2_title_maxlen");
        $title_maxnum = GetSettingValue("class_titlelist_maxlen");

        $rs = db_query($connid, "select * from class_info where parent_channel=%d order by class_name", array($portalpage->_channel_id));
        if ($rs) {
            $html = "";
            $list = db_result($rs);
            if (isset($list[0])) {
                $empty = FALSE;
                foreach ($list as $item) {
                    $contentlist = new ContentList($connid);
                    $contentlist->SetTitleList($title_maxnum, $title_maxlen, TRUE, FALSE, FALSE, 1, TRUE);
                    $contentlist->SetClass($item["id"], $item["class_name"], $type);
                    $box = new Box(2);
                    $box->SetTitle($item["class_name"]);
                    $box->SetContent($contentlist->GetHTML(1, 1), "left", "top", 10);
//					if($html!=""){
//						$html="<table cellpadding=0 cellspacing=0><tr><td valign=\"top\">{$html}</td><td valign=\"top\">{$box->GetHTML()}</td></tr></table>";
//						$portalpage->AddToLeft($html);
//						$html="";
//					}else{
//						$html= $box->GetHTML();
//					}
                    $portalpage->AddToLeft($box);
                }
//				if($html!="") $portalpage->AddToLeft($html);
            }
            db_free($rs);
        }
        if ($empty) {
            $html = "对不起！" . $portalpage->_channel_name . "正在建设中，请访问本站其它频道";
            $box = new Box(3);
            $box->SetTitle($portalpage->_channel_name . "正在建设中...");
            $box->SetContent($html, "center", "top", 5);
            $portalpage->AddToLeft($box);
        }
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
    $portalpage->ShowNavBox1(TRUE);
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