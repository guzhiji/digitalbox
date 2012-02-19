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
require("modules/boxes/BannerBox.class.php");
require("modules/boxes/TopBox.class.php");
require("modules/boxes/AdsBox.class.php");
require("modules/boxes/NoticeBoardBox.class.php");
require("modules/boxes/SearchBox.class.php");
require("modules/boxes/ChannelNaviBox.class.php");
require("modules/boxes/ClassNaviBox.class.php");
require("modules/boxes/BottomNaviBox.class.php");
require("modules/boxes/CalendarBox.class.php");
require("modules/boxes/VoteBox.class.php");
require("modules/boxes/FriendSiteBox.class.php");
require("modules/boxes/StyleListBox.class.php");

//change style
$style_id = trim(strPost("style"));
$from_address = strPost("from");

if (is_numeric($style_id) && strlen($from_address) > 10) {
    $rs = db_query("select id from style_info where id=%d", array($style_id));
    if ($rs) {
        db_free($rs);
        setcookie(dbPrefix . "_Style", $style_id, time() + 7 * 24 * 60 * 60);
        header("Location: " . $from_address);
        exit();
    }
}

$portalpage = new PortalPage();
$portalpage->AddBox(new BannerBox());
//left
$portalpage->AddBox(new AdsBox("ad_1", TRUE));
if (GetSettingValue("style_changeable")) {
    $portalpage->AddBox(new StyleListBox());
} else {
    $portalpage->AddBox(new MsgBox(GetLangData("moduleclosed"), GetLangData("changetheme"), "index.php"));
}
$portalpage->AddBox(new AdsBox("ad_2", TRUE));
//right
$portalpage->AddBox(new NoticeBoardBox());
$portalpage->AddBox(new SearchBox(FALSE));
$portalpage->AddBox(new ChannelNaviBox("Right", TRUE));
$portalpage->AddBox(new AdsBox("ad_3", FALSE));
$portalpage->AddBox(new CalendarBox());
$portalpage->AddBox(new VoteBox());
$portalpage->AddBox(new FriendSiteBox());
$portalpage->AddBox(new AdsBox("ad_4", FALSE));

$portalpage->SetTitle(GetLangData("changetheme"));
$portalpage->Show();
