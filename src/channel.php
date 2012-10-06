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
require("modules/ContentList.class.php");
require("modules/boxfactories/ClassBoxList.class.php");
require("modules/boxes/TopBox.class.php");
require("modules/boxes/AdsBox.class.php");
require("modules/boxes/NoticeBoardBox.class.php");
require("modules/boxes/SearchBox.class.php");
require("modules/boxes/ClassNaviBox.class.php");
require("modules/boxes/CalendarBox.class.php");
require("modules/boxes/VoteBox.class.php");
require("modules/boxes/GuestBookBox.class.php");
require("modules/boxes/FriendSiteBox.class.php");

global $_error;
$_error = FALSE;
$id = trim(strGet("id"));
$type = GetTypeNumber(trim(strGet("type")));
if ($type > 0) {
    $rs = db_query("select channel_name from channel_info where channel_type=%d and id=%d", array($type, $id));
    if (!$rs) {
        $_error = TRUE;
    } else {
        $list = db_result($rs);
        if (isset($list[0])) {
            $item = $list[0];

            global $_channelID;
            global $_channelName;
            global $_channelType;
            $_channelID = intval($id);
            $_channelName = $item["channel_name"];
            $_channelType = $type;
        } else {
            $_error = TRUE;
        }
        db_free($rs);
    }
} else {
    $_error = TRUE;
}

$portalpage = new PortalPage();

//left
$portalpage->AddBox(new TopBox("popular", 3));
$portalpage->AddBox(new AdsBox("ad_1", TRUE));

//classes
if (!$_error) {
    $portalpage->AddKeywords($_channelName);
    $portalpage->AddBoxFactory(new ClassBoxList());
    $portalpage->AddBox(new GuestBookBox(GetTypeName($_channelType, 0)));
} else {
    $portalpage->AddBox(new MsgBox(GetLangData("notfound"), GetLangData("error")));
}

$portalpage->AddBox(new AdsBox("ad_2", TRUE));
//right
$portalpage->AddBox(new NoticeBoardBox());
$portalpage->AddBox(new SearchBox(TRUE));
$portalpage->AddBox(new ChannelNaviBox("Right", TRUE));
if (!$_error)
    $portalpage->AddBox(new ClassNaviBox($_channelID, $_channelName, $_channelType, FALSE));
$portalpage->AddBox(new AdsBox("ad_3", FALSE));
$portalpage->AddBox(new CalendarBox());
$portalpage->AddBox(new VoteBox());
$portalpage->AddBox(new FriendSiteBox());
$portalpage->AddBox(new AdsBox("ad_4", FALSE));

if (!$_error) {
    $portalpage->SetTitle($_channelName);
} else {
    $portalpage->SetTitle(GetLangData("error"));
}
$portalpage->Show();
