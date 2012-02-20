<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require("modules/common.module.php");
require("modules/pages/PortalPage.class.php");
require("modules/ContentList.class.php");
require("modules/boxes/SearchListBox.class.php");
require("modules/boxes/TopBox.class.php");
require("modules/boxes/AdsBox.class.php");
require("modules/boxes/NoticeBoardBox.class.php");
require("modules/boxes/SearchBox.class.php");
require("modules/boxes/CalendarBox.class.php");
require("modules/boxes/VoteBox.class.php");
require("modules/boxes/GuestBookBox.class.php");
require("modules/boxes/FriendSiteBox.class.php");

$portalpage = new PortalPage();

$portalpage->AddBox(new AdsBox("ad_1", TRUE));
//left
if (GetSettingValue("search_visible")) {
    $portalpage->AddBox(new SearchListBox());
} else {
    $portalpage->AddBox(new MsgBox(GetLangData("moduleclosed"), GetLangData("search")));
}

$portalpage->AddBox(new AdsBox("ad_2", TRUE));
//right
$portalpage->AddBox(new SearchBox(FALSE));
$portalpage->AddBox(new NoticeBoardBox());
$portalpage->AddBox(new ChannelNaviBox("Right", TRUE));
$portalpage->AddBox(new AdsBox("ad_3", FALSE));
$portalpage->AddBox(new CalendarBox());
$portalpage->AddBox(new VoteBox());
$portalpage->AddBox(new GuestBookBox());
$portalpage->AddBox(new FriendSiteBox());
$portalpage->AddBox(new AdsBox("ad_4", FALSE));
$portalpage->SetTitle(GetLangData("search"));
$portalpage->Show();
