<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  style.php
  ------------------------------------------------------------------
 */
require("modules/view/PortalPage.class.php");
require("modules/view/Box.class.php");
require("modules/view/ContentList.class.php");
require("modules/common.module.php");

$portalpage = new PortalPage();
$connid = $portalpage->GetDBConn();

//change style
$style_id = trim(strPost("style"));
$from_address = strPost("from");

if (is_numeric($style_id) && strlen($from_address) > 10) {
    $rs = db_query($connid, "select id from style_info where id=%d", array($style_id));
    if ($rs) {
        db_free($rs);
        setcookie(dbPrefix . "_Style", $style_id, time() + 7 * 24 * 60 * 60);
        header("Location: " . $from_address);
        exit();
    }
}
//left

if (GetSettingValue("style_changeable")) {

    $portalpage->AddAdsBox(GetSettingValue("ad_1"), TRUE);

    $stylelist = new ListView("stylelist_select_item");
    $stylelist->SetContainer("stylelist_select", array(
        "Referrer" => array_key_exists("HTTP_REFERER", $_SERVER) ? $_SERVER["HTTP_REFERER"] : ""
    ));
    $style = GetSettingValue("style_id");

    $rs = db_query($connid, "SELECT * FROM style_info");
    if ($rs) {
        $list = db_result($rs);
        foreach ($list as $item) {
            $stylelist->AddItem(array(
                "ID" => $item["id"],
                "Checked" => $style == intval($item["id"]) ? " checked=\"checked\"" : "",
                "Name" => $item["style_name"]
            ));
        }
        db_free($rs);
    }

    $box = new Box(3);
    $box->SetTitle("选择风格");
    $box->SetContent($stylelist->GetHTML(), "center", "middle", 5);

    $portalpage->AddToLeft($box);

    $portalpage->AddAdsBox(GetSettingValue("ad_2"), TRUE);
} else {
    $portalpage->ShowInfo("风格改变功能已被关闭", "风格", "index.php");
}

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

$portalpage->SetTitle("选择风格");
$portalpage->Show();

$portalpage->CloseDBConn();
?>
