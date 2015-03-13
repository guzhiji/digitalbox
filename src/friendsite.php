<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  friendsite.php
  ------------------------------------------------------------------
 */
require("modules/view/PortalPage.class.php");
require("modules/view/Box.class.php");
require("modules/common.module.php");

$portalpage = new PortalPage();
$connid = $portalpage->GetDBConn();

//left


$box = new Box(3);
$box->SetTitle("友情链接");

if (GetSettingValue("friendsite_visible")) {

    $portalpage->AddAdsBox(GetSettingValue("ad_1"), TRUE);

    require_once("modules/view/SiteList.class.php");
    $sl = new SiteList("sitelist_item", "sitelist_empty");
    $sl->SetContainer("sitelist_container", 2);
    $sl->Bind($connid);

    $box->SetContent($sl->GetHTML(), "center", "top", 5);

    $portalpage->AddAdsBox(GetSettingValue("ad_2"), TRUE);
} else {
    $box->SetContent("“友情链接”已经被关闭，请与管理员联系", "center", "middle", 5);
}

$portalpage->AddToLeft($box);


//right
$portalpage->ShowNoticeBoard();
$portalpage->ShowSearchBox("站内搜索", 0);
$portalpage->ShowNavBox1(TRUE);

$portalpage->AddAdsBox(GetSettingValue("ad_3"), FALSE);

$portalpage->ShowCalendarBox();
$portalpage->ShowVoteBox();
$portalpage->ShowGuestBookBox("");

$portalpage->AddAdsBox(GetSettingValue("ad_4"), FALSE);

$portalpage->SetTitle("友情链接");
$portalpage->Show();

$portalpage->CloseDBConn();
?>