<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  guestbook.php
  ------------------------------------------------------------------
 */
require("modules/view/PortalPage.class.php");
require("modules/view/Box.class.php");
require("modules/view/ContentList.class.php");
require("modules/common.module.php");
require("modules/view/GuestBook.class.php");

$portalpage = new PortalPage();
$connid = $portalpage->GetDBConn();

$err_tip = "";

if (!GetSettingValue("guestbook_visible"))
    $err_tip = "留言本已经被关闭，请与管理员联系;";
$page_mode = trim(strGet("mode"));

if (strGet("function") == "add") {
    require("modules/data/comment.module.php");
    $comment = new Comment($connid);
    if ($comment->check()) {
        $comment->add();
    } else {
        $err_tip = $comment->error;
    }
}

if ($err_tip == "") {

    $portalpage->AddAdsBox(GetSettingValue("ad_1"), TRUE);

    $guestbook = new GuestBook($portalpage, strGet("mode"), GetSettingValue("guestbook_list_maxlen"));
    $guestbook->ShowControlBox();
    if ($guestbook->mode == "add") {
        $guestbook->ShowEditor();
    } else {
        $guestbook->ShowCommentList();
    }

    $portalpage->AddAdsBox(GetSettingValue("ad_2"), TRUE);
} else {
    $portalpage->ShowInfo(ErrorList($err_tip), "错 误 - 留 言 本", "back");
}

$portalpage->ShowNoticeBoard();
$portalpage->ShowSearchBox("站内搜索", 0);
$portalpage->ShowNavBox1(TRUE);

$portalpage->AddAdsBox(GetSettingValue("ad_3"), FALSE);

$portalpage->ShowCalendarBox();
$portalpage->ShowVoteBox();
$portalpage->ShowFriendListBox();

$portalpage->AddAdsBox(GetSettingValue("ad_4"), FALSE);

$portalpage->SetTitle("留言本");
$portalpage->Show();

$portalpage->CloseDBConn();
?>
