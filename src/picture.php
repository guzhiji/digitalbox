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
require("modules/boxfactories/PictureBoxFactory.class.php");
require("modules/boxes/TopBox.class.php");
require("modules/boxes/AdsBox.class.php");
require("modules/boxes/NoticeBoardBox.class.php");
require("modules/boxes/SearchBox.class.php");
require("modules/boxes/ClassNaviBox.class.php");
require("modules/boxes/CalendarBox.class.php");
require("modules/boxes/VoteBox.class.php");
require("modules/boxes/FriendSiteBox.class.php");

$id = trim(strGet("id"));

$sql = new SQL_Content();
$sql->AddField("picture_info.picture_add");
$sql->AddField("picture_info.picture_text");
$sql->SetContentID($id);
$sql->SetOrder(1);
$sql->SetMode(2);

global $_error;
$_error = FALSE;
$rs = db_query($sql->GetSelect());
if (!$rs) {
    $_error = TRUE;
} else {

    $list = db_result($rs);
    if (isset($list[0])) {
        $item = $list[0];

        global $_channelID;
        global $_channelName;
        global $_channelType;
        $_channelID = $item["channel_id"];
        $_channelName = $item["channel_name"];
        $_channelType = 2;

        global $_classID;
        global $_className;
        $_classID = $item["class_id"];
        $_className = $item["class_name"];

        global $_contentID;
        global $_contentName;
        global $_contentTime;
        global $_contentVCount;
        $_contentID = intval($id);
        $_contentName = $item["content_name"];
        $_contentTime = $item["content_time"];
        $_contentVCount = intval($item["visitor_count"]);

        global $_pictureAddr;
        global $_pictureText;
        $_pictureAddr = $item["picture_add"];
        $_pictureText = $item["picture_text"];


        //add 1 to visitorcount
        db_query("UPDATE picture_info SET visitor_count=visitor_count+1 WHERE id=%d", array($id));
        $_contentVCount++;
    } else {
        $_error = TRUE;
    }
    db_free($rs);
}



$portalpage = new PortalPage();

//left
$portalpage->AddBox(new TopBox("popular", 3));
$portalpage->AddBox(new AdsBox("ad_1", TRUE));


//left
if (!$_error) {
    $portalpage->AddKeywords($_contentName);
    $portalpage->AddKeywords($_className);
    $portalpage->AddKeywords($_channelName);
    $portalpage->AddBoxFactory(new PictureBoxFactory());
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
    $portalpage->SetTitle($_contentName . " - " . $_className . " - " . $_channelName);
} else {
    $portalpage->SetTitle(GetLangData("error"));
}
$portalpage->Show();
