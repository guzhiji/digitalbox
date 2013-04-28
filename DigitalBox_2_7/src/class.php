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
require("modules/boxes/ContentListBox.class.php");
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
$_error = !validateClass(strGet("id"), strGet("type"));

// $_error = FALSE;
// $id = trim(strGet("id"));
// $type = GetTypeNumber(trim(strGet("type")));
// if ($type > 0) {
//     $rs = db_query("select class_info.class_name,channel_info.id as channel_id,channel_info.channel_name from class_info,channel_info where channel_info.channel_type=%d and class_info.parent_channel=channel_info.id and class_info.id=%d", array($type, $id));
//     if (!$rs) {
//         $_error = TRUE;
//     } else {
//         $list = db_result($rs);
//         if (isset($list[0])) {

//             $item = $list[0];

//             global $_channelID;
//             global $_channelName;
//             global $_channelType;
//             $_channelID = $item["channel_id"];
//             $_channelName = $item["channel_name"];
//             $_channelType = $type;

//             global $_classID;
//             global $_className;
//             $_classID = intval($id);
//             $_className = $item["class_name"];
//         } else {
//             $_error = TRUE;
//         }
//         db_free($rs);
//     }
// } else {
//     $_error = TRUE;
// }

$portalpage = new PortalPage();

$portalpage->AddBox(new SearchBox(TRUE));
//left

$portalpage->AddBox(new TopBox("popular", 3));

$portalpage->AddBox(new AdsBox("ad_1", TRUE));

if (!$_error) {
    $portalpage->AddKeywords($_className);
    $portalpage->AddKeywords($_channelName);

    $portalpage->AddBox(new ContentListBox());

    $portalpage->AddBox(new GuestBookBox(GetTypeName($_channelType, 0)));
} else {
    $portalpage->AddBox(new MsgBox(GetLangData("notfound"), GetLangData("error")));
}

$portalpage->AddBox(new AdsBox("ad_2", TRUE));
//right
$portalpage->AddBox(new NoticeBoardBox());
//$portalpage->AddBox(new SearchBox(TRUE));
if (!$_error)
    $portalpage->AddBox(new ClassNaviBox($_channelID, $_channelName, $_channelType, TRUE));
$portalpage->AddBox(new AdsBox("ad_3", FALSE));
$portalpage->AddBox(new CalendarBox());
$portalpage->AddBox(new VoteBox());
$portalpage->AddBox(new FriendSiteBox());
$portalpage->AddBox(new AdsBox("ad_4", FALSE));

if (!$_error) {
    $portalpage->SetTitle($_className . " - " . $_channelName);
} else {
    $portalpage->SetTitle(GetLangData("error"));
}
$portalpage->Show();
