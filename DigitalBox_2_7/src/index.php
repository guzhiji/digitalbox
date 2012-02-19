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
require("modules/boxfactories/ChannelBoxList.class.php");
require("modules/boxes/BannerBox.class.php");
require("modules/boxes/TopBox.class.php");
require("modules/boxes/AdsBox.class.php");
require("modules/boxes/NoticeBoardBox.class.php");
require("modules/boxes/SearchBox.class.php");
require("modules/boxes/ChannelNaviBox.class.php");
require("modules/boxes/BottomNaviBox.class.php");
require("modules/boxes/CalendarBox.class.php");
require("modules/boxes/VoteBox.class.php");
require("modules/boxes/GuestBookBox.class.php");
require("modules/boxes/FriendSiteBox.class.php");

$portalpage = new PortalPage();
$portalpage->AddBox(new BannerBox());
//left
$portalpage->AddBox(new TopBox("new", 2));
$portalpage->AddBox(new TopBox("popular", 2));
$portalpage->AddBox(new AdsBox("ad_1", TRUE));
$portalpage->AddBoxFactory(new ChannelBoxList());
$portalpage->AddBox(new AdsBox("ad_2", TRUE));
//right
$portalpage->AddBox(new NoticeBoardBox());
$portalpage->AddBox(new SearchBox(TRUE));
$portalpage->AddBox(new ChannelNaviBox("Right", FALSE));
$portalpage->AddBox(new AdsBox("ad_3", FALSE));
$portalpage->AddBox(new CalendarBox());
$portalpage->AddBox(new VoteBox());
$portalpage->AddBox(new GuestBookBox());
$portalpage->AddBox(new FriendSiteBox());
$portalpage->AddBox(new AdsBox("ad_4", FALSE));

$portalpage->SetTitle(GetLangData("homepage"));
$portalpage->Show();
