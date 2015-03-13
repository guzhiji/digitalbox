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
require("modules/boxes/TopBox.class.php");
require("modules/boxes/AdsBox.class.php");
require("modules/boxes/NoticeBoardBox.class.php");
require("modules/boxes/SearchBox.class.php");
require("modules/boxes/ClassNaviBox.class.php");
require("modules/boxes/CalendarBox.class.php");
require("modules/boxes/VoteBox.class.php");
require("modules/boxes/FriendSiteBox.class.php");

$portalpage = new PortalPage();
//left
$portalpage->AddBox(new AdsBox("ad_1", TRUE));

if (GetSettingValue("guestbook_visible")) {

    if (strGet("function") == "add") {
        require("modules/data/comment.module.php");
        $comment = new Comment($connid);
        if ($comment->check()) {
            $comment->add();
            $portalpage->AddBox(new MsgBox(GetLangData("published"), GetLangData("guestbook"), "guestbook.php"));
        } else {
            $portalpage->AddBox(new MsgBox(ErrorList($comment->error), GetLangData("error"), "back"));
        }
    } else {
        require("modules/boxes/GuestbookCtlBox.class.php");
        $ctlbox = new GuestbookCtlBox();
        $portalpage->AddBox($ctlbox);
        if (strtolower(strGet("mode")) == "add") {
            require("modules/boxes/GuestbookEditor.class.php");
            $portalpage->AddBox(new GuestbookEditor());
        } else {
            require("modules/boxfactories/GuestbookMsgList.class.php");
            $portalpage->AddBoxFactory(new GuestbookMsgList($ctlbox->GetPageNumber()));
        }
    }
} else {
    $portalpage->AddBox(new MsgBox(ErrorList(GetLangData("moduleclosed")), GetLangData("guestbook"), "back"));
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

$portalpage->SetTitle(GetLangData("guestbook"));
$portalpage->Show();
