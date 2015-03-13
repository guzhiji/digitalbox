<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  index.php
  ------------------------------------------------------------------
 */
require("modules/view/PortalPage.class.php");
require("modules/view/Box.class.php");
require("modules/view/ContentList.class.php");
require("modules/common.module.php");

$portalpage = new PortalPage();
$connid = $portalpage->GetDBConn();

//left

$title_maxnum = GetSettingValue("toplist_maxlen");
$title_maxlen = GetSettingValue("box2_title_maxlen");

$html = "";
$sql = new SQL_Content();

for ($a = 1; $a <= 2; $a++) {
    $sql->SetOrder($a);

    $rs = db_query($connid, $sql->GetSelect() . " LIMIT 0,$title_maxnum");
    $titlelist = new TitleList();
    $titlelist->ShowIcon(TRUE);
    $titlelist->ShowExtraInfo($a);
    $titlelist->SetMaxLength($title_maxlen);
    $titlelist->SetLinkType(1);
    $b = 0;
    if ($rs) {
        $list = db_result($rs);
        foreach ($list as $item) {
            $titlelist->CreateItem($item["channel_type"]);
            $titlelist->SetItemContent($item["content_id"], $item["content_name"], $item["content_time"], $item["visitor_count"]);
            $titlelist->SetItemClass($item["class_id"], $item["class_name"]);
            $titlelist->SetItemChannel($item["channel_id"], $item["channel_name"]);
            $titlelist->AddItem();
        }
        db_free($rs);
    }
    $box = new Box(2);
    switch ($a) {
        case 1:
            $box->SetTitle("最新内容");
            break;
        case 2:
            $box->SetTitle("最热内容");
            break;
    }
    $box->SetContent($titlelist->GetHTML(), "left", "top", 10);
    //$html.="<td valign=\"top\">" . $box->GetHTML() . "</td>";
    $portalpage->AddToLeft($box);
}
//$portalpage->AddToLeft ("<table cellpadding=0 cellspacing=0 border=0><tr>$html</tr></table>");


$portalpage->AddAdsBox(GetSettingValue("ad_1"), TRUE);


$title_maxnum = GetSettingValue("channel_titlelist_maxlen");
$title_maxlen = GetSettingValue("box3_title_maxlen");
$image_maxrow = GetSettingValue("index_grid_maxrow");

$empty = TRUE;
$rs = db_query($connid, "select * from channel_info where channel_type!=0 order by channel_type");
if ($rs) {
    $list = db_result($rs);
    $empty = !isset($list[0]);
    foreach ($list as $item) {
        //$portalpage->ShowContentList($item["channel_type"],$item["id"],$item["channel_name"],"",1,TRUE);
        $contentlist = new ContentList($connid);
        $contentlist->SetTitleList($title_maxnum, $title_maxlen, TRUE, FALSE, TRUE, 1);
        $contentlist->SetImageList($image_maxrow, 5, 1);
        $contentlist->SetChannel($item["id"], $item["channel_name"], $item["channel_type"]);
        $box = new Box(3);
        $box->SetTitle($item["channel_name"]);
        $box->SetContent($contentlist->GetHTML(1, 1), "left", "top", 5);
        $portalpage->AddToLeft($box);
    }
    db_free($rs);
}
if ($empty) {
    $box = new Box(3);
    $box->SetTitle("本站正在建设中...");
    $box->SetContent("对不起！本站正在建设中，请下次光临....", "center", "middle", 10);

    $portalpage->AddToLeft($box);
}

$portalpage->AddAdsBox(GetSettingValue("ad_2"), TRUE);

//right
$portalpage->ShowNoticeBoard();
$portalpage->ShowSearchBox("站内搜索", 0);
$portalpage->ShowNavBox1(FALSE);

$portalpage->AddAdsBox(GetSettingValue("ad_3"), FALSE);

$portalpage->ShowCalendarBox();
$portalpage->ShowVoteBox();
$portalpage->ShowGuestBookBox("");
$portalpage->ShowFriendListBox();

$portalpage->AddAdsBox(GetSettingValue("ad_4"), FALSE);

$portalpage->SetTitle("首　页");
$portalpage->Show();

$portalpage->CloseDBConn();
?>
