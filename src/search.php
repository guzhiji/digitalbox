<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  search.php
  ------------------------------------------------------------------
 */
require("modules/view/PortalPage.class.php");
require("modules/view/Box.class.php");
require("modules/view/PagingBar.class.php");
require("modules/common.module.php");
require("modules/data/sql_content.module.php");

$portalpage = new PortalPage();
$connid = $portalpage->GetDBConn();

//left

$searchkey = urldecode(strGet("searchkey"));

$title_maxlen = GetSettingValue("box2_title_maxlen");

$sql = new SQL_Content();
if (GetSettingValue("search_visible")) {

    $portalpage->AddAdsBox(GetSettingValue("ad_1"), TRUE);

    $sql->SetSearchKey($searchkey);
    if ($sql->_searchkey != "") {
        $boxtitle = "搜索结果";

        $sql->SetOrder(1);
        $sql->SetChannelID(strGet("channel"));
        $sql->SetClassID(strGet("class"));
        $sql->SetMode(strGet("mode"));

        $portalpage->_class_id = $sql->_classid;
        $portalpage->_channel_id = $sql->_channelid;
        //$portalpage->_mode =

        $page_size = GetSettingValue("general_list_maxlen");
        $total_rec = 0;
        $rs = db_query($connid, $sql->GetCountQuery());
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0]))
                $total_rec = intval($list[0]["c"]);
            db_free($rs);
        }
        if ($total_rec > 0) {

            $pb = new PagingBar();
            $pb->SetPageCount($total_rec, $page_size);
            $total_page = $pb->GetPageCount();
            $current_page = $pb->GetPageNumber();

            $rs = db_query($connid, $sql->GetSelect() . " LIMIT " . (($current_page - 1) * $page_size) . ",$page_size");
            if ($rs) {

                $sl = new ListView("search_item");
                $sl->SetContainer("search_container", array(
                    "TotalCount" => $total_rec,
                    "SearchKey" => $searchkey,
                    "PagingBar" => $pb->GetHTML()
                ));

                $list = db_result($rs);
                foreach ($list as $item) {
                    $typename = GetTypeName($item["channel_type"], 1);

                    $sl->AddItem(array(
                        "Content_Name" => $item["content_name"],
                        "Content_Link" => $typename . ".php?mode=content&id=" . $item["content_id"],
                        "Content_ShortName" => Len_Control($item["content_name"], $title_maxlen),
                        "Content_Type" => $item["channel_type"],
                        "Channel_Name" => $item["channel_name"],
                        "Channel_Link" => $typename . ".php?mode=channel&id=" . $item[$sql->_channelid],
                        "Channel_ShortName" => Len_Control($item["channel_name"], $title_maxlen),
                        "Class_Name" => $item["class_name"],
                        "Class_Link" => $typename . ".php?mode=class&id=" . $item[$sql->_classid],
                        "Class_ShortName" => Len_Control($item["class_name"], $title_maxlen),
                        "VCount" => $item["visitor_count"],
                        "Time" => $item["content_time"]
                    ));
                }
                db_free($rs);

                $box = new Box(3);
                $box->SetTitle($boxtitle);
                $box->SetContent($sl->GetHTML(), "center", "middle", 5);

                $portalpage->AddToLeft($box);
            } else {
                $portalpage->ShowInfo("发生意外错误", $boxtitle, "index.php");
            }
        } else {

            $html = "<div align=\"center\">找不到您搜索的“" . $searchkey . "”</div>";
            if ($sql->_mode > 0)
                $html .= "<div align=\"center\">您可以点击<a href=\"search.php?mode=0&searchkey=" . urlencode($searchkey) . "\">这儿</a>在全站进行搜索</div>";

            $portalpage->ShowInfo($html, $boxtitle);
        }
    }else {
        $portalpage->ShowInfo("没有搜索关键词！", "请输入关键词");
    }

    $portalpage->AddAdsBox(GetSettingValue("ad_2"), TRUE);
} else {
    $portalpage->ShowInfo("站内搜索功能已经被管理员关闭，请与管理员联系。", "已关闭");
}

//right
$portalpage->ShowNoticeBoard();

$a = 1;
if ($portalpage->_class_id > 0) {

    $rs = db_query($connid, "select class_name from class_info where id=%d", array($portalpage->_class_id));
    if ($rs) {
        $a = 0;
        $list = db_result($rs);
        $item = $list[0];
        $portalpage->ShowSearchBox("搜索栏目：" . $item["class_name"], $sql->_mode, FALSE);
    }
    db_free($rs);
} else if ($portalpage->_channel_id > 0) {

    $rs = db_query($connid, "select channel_name from channel_info where id=%d", array($portalpage->_channel_id));
    if ($rs) {
        $a = 0;
        $list = db_result($rs);
        $item = $list[0];
        $portalpage->ShowSearchBox("搜索频道：" . $item["channel_name"], $sql->_mode, FALSE);
    }
    db_free($rs);
}
if ($a == 1) {
    if ($sql->_mode == 0) {
        $portalpage->ShowSearchBox("站内搜索", 0, FALSE);
    } else {
        $portalpage->ShowSearchBox(GetTypeName($sql->_mode, 0) . "搜索", $sql->_mode, FALSE);
    }
}

$portalpage->ShowNavBox1(TRUE);

$portalpage->AddAdsBox(GetSettingValue("ad_3"), FALSE);

$portalpage->ShowCalendarBox();
$portalpage->ShowVoteBox();
$portalpage->ShowGuestBookBox("");
$portalpage->ShowFriendListBox();

$portalpage->AddAdsBox(GetSettingValue("ad_4"), FALSE);

$portalpage->SetTitle("搜　索");
$portalpage->Show();

$portalpage->CloseDBConn();
?>
