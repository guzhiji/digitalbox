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
require("modules/boxfactories/SoftwareBoxFactory.class.php");
require("modules/boxes/TopBox.class.php");
require("modules/boxes/AdsBox.class.php");
require("modules/boxes/NoticeBoardBox.class.php");
require("modules/boxes/SearchBox.class.php");
require("modules/boxes/ClassNaviBox.class.php");
require("modules/boxes/CalendarBox.class.php");
require("modules/boxes/VoteBox.class.php");
require("modules/boxes/FriendSiteBox.class.php");

global $_error;
$_error = FALSE;
$id = trim(strGet("id"));

if (strGet("mode") == "download") {
    $addr = "";

    if (isset($_SERVER["HTTP_REFERER"])) {

        $rs = db_query("SELECT software_add FROM software_info WHERE id=%d", array($id));
        if ($rs) {
            $list = db_result($rs);

            if (isset($list[0])) {
                $addr = $list[0]["software_add"];
            }
            db_free($rs);
        }
    }

    if ($addr != "") {
        //add 1 to visitorcount
        db_query("UPDATE software_info SET visitor_count=visitor_count+1 WHERE id=%d", array($id));
        db_close();
        PageRedirect($addr);
    } else {
        $_error = TRUE;
    }
}

if (!$_error) {
    $sql = new SQL_Content();
    $sql->AddField("software_info.software_name");
    $sql->AddField("software_info.software_time");
    $sql->AddField("software_info.software_producer");
    $sql->AddField("software_info.software_language");
    $sql->AddField("software_info.software_grade");
    $sql->AddField("software_info.software_size");
    $sql->AddField("software_info.software_environment");
    $sql->AddField("software_info.software_type");
    $sql->AddField("software_info.software_text");
    $sql->AddField("software_info.visitor_count");
    $sql->AddField("software_info.parent_class");
    $sql->SetContentID($id);
    $sql->SetOrder(1);
    $sql->SetMode(4);

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
            $_channelType = 4;

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
            $_contentTime = $item["software_time"];
            $_contentVCount = intval($item["visitor_count"]);

            global $software_producer;
            global $software_language;
            global $software_grade;
            global $software_size;
            global $software_environment;
            global $software_type;
            global $software_text;
            $software_producer = $item["software_producer"];
            $software_language = $item["software_language"];
            $software_grade = $item["software_grade"];
            $software_size = $item["software_size"];
            $software_environment = $item["software_environment"];
            $software_type = $item["software_type"];
            $software_text = $item["software_text"];
        } else {
            $_error = TRUE;
        }

        db_free($rs);
    }
}

$portalpage = new PortalPage();

//left
$portalpage->AddBox(new TopBox("popular", 3));
$portalpage->AddBox(new AdsBox("ad_1", TRUE));

if (!$_error) {
    $portalpage->AddKeywords($_contentName);
    $portalpage->AddKeywords($_className);
    $portalpage->AddKeywords($_channelName);
    $portalpage->AddBoxFactory(new SoftwareBoxFactory());
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
