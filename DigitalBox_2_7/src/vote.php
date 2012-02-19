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

$errortips = "";

if (!GetSettingValue("vote_visible")) {
    $errortips .= GetLangData("novote") . ";";
} else {
    //process vote
    if (GetSettingValue("vote_on") && strtolower(strGet("command")) == "vote") {
        if (intval(strPost("id")) > 0) {
            if (strCookie("Voted") == "") {
                if (db_query("update vote_info set vote_value=vote_value+1 where id=%d", array(strPost("id")))) {
                    setcookie(dbPrefix . "_Voted", "TRUE", time() + 7 * 24 * 60 * 60);
                }
            } else {
                $errortips .= GetLangData("voted") . ";";
            }
        } else {
            $errortips.= GetLangData("nothingchosen") . ";";
        }
    }
}


$portalpage = new PortalPage();
$portalpage->AddBox(new BannerBox());

//left
$portalpage->AddBox(new AdsBox("ad_1", TRUE));

//left
if ($errortips != "") {
    $portalpage->AddBox(new MsgBox(ErrorList($errortips), GetLangData("error"), "index.php"));
} else {
    $portalpage->AddBox(new VoteBox(3));
}

$portalpage->AddBox(new AdsBox("ad_2", TRUE));
//right
$portalpage->AddBox(new NoticeBoardBox());
$portalpage->AddBox(new SearchBox(FALSE));
$portalpage->AddBox(new ChannelNaviBox("Right", TRUE));
$portalpage->AddBox(new AdsBox("ad_3", FALSE));
$portalpage->AddBox(new CalendarBox());
$portalpage->AddBox(new FriendSiteBox());
$portalpage->AddBox(new AdsBox("ad_4", FALSE));

$portalpage->SetTitle(GetLangData("vote"));
$portalpage->Show();
