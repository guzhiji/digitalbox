<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require("modules/common.module.php");
require("modules/pages/PortalPage.class.php");

$portalpage = new PortalPage();
$portalpage->Process(array(
    "boxes" => array(
        array("TopBox", array("new", 2)),
        array("TopBox", array("popular", 2)),
        array("AdsBox", array("ad_1", TRUE))
    )
));
$portalpage->Process(array(
    "boxfactory" => array("ChannelBoxList", NULL)
));
$portalpage->Process(array(
    "boxes" => array(
        array("AdsBox", array("ad_2", TRUE)),
        array("NoticeBoardBox", NULL),
        array("SearchBox", array(TRUE)),
        array("ChannelNaviBox", array("Right", FALSE)),
        array("AdsBox", array("ad_3", FALSE)),
        array("CalendarBox", NULL),
        array("VoteBox", NULL),
        array("GuestBookBox", NULL),
        array("FriendSiteBox", NULL),
        array("AdsBox", array("ad_4", FALSE))
    )
));

$portalpage->SetTitle(GetLangData("homepage"));
$portalpage->Show();
