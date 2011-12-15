<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  article.php
  ------------------------------------------------------------------
 */
require("modules/view/PortalPage.class.php");
require("modules/view/Box.class.php");
require("modules/view/ContentList.class.php");
require("modules/common.module.php");

$portalpage = new PortalPage();
$connid = $portalpage->GetDBConn();

$id = trim(strGet("id"));
$sql = new SQL_Content();
$sql->AddField("article_info.article_author");
$sql->AddField("article_info.article_text");
$sql->SetContentID($id);
$sql->SetOrder(1);
$sql->SetMode(1);

$rs = db_query($connid, $sql->GetSelect());
if (!$rs) {
    $portalpage->RaiseError();
} else {
    $list = db_result($rs);
    if (isset($list[0])) {
        $item = $list[0];
        $portalpage->SetChannel($item["channel_id"], $item["channel_name"]);
        $portalpage->SetClass($item["class_id"], $item["class_name"]);
        $portalpage->SetContent($id, $item["content_name"]);

        $article_time = $item["content_time"];
        $visitor_count = intval($item["visitor_count"]);
        $article_author = $item["article_author"];
        $article_text = $item["article_text"];

        $portalpage->AddKeywords($item["content_name"]);
        $portalpage->AddKeywords($item["class_name"]);
        $portalpage->AddKeywords($item["channel_name"]);

        //add 1 to visitorcount
        db_query($connid, "UPDATE article_info SET visitor_count=visitor_count+1 WHERE id=%d", array($id));
        $visitor_count++;
    } else {
        $portalpage->RaiseError();
    }
    db_free($rs);
}

//left
if (!$portalpage->HasError()) {

    //hottest
    $title_maxlen = GetSettingValue("box3_title_maxlen");
    $title_maxnum = GetSettingValue("toplist_maxlen");

    $sql = new SQL_Content();
    $sql->SetMode(1);
    $sql->SetOrder(2);
    $sql->SetChannelID($portalpage->_channel_id);

    $rs = db_query($connid, $sql->GetSelect() . " LIMIT 0,$title_maxnum");
    $titlelist = new TitleList();
    $titlelist->SetMaxLength($title_maxlen);
    $titlelist->ShowIcon(TRUE);
    $titlelist->SetLinkType(1);
    $titlelist->ShowExtraInfo(3);
    if ($rs) {
        $list = db_result($rs);
        foreach ($list as $item) {
            $titlelist->CreateItem($item["channel_type"]);
            $titlelist->SetItemContent($item["content_id"], $item["content_name"], $item["content_time"], $item["visitor_count"]);
            $titlelist->SetItemClass($item["class_id"], $item["class_name"]);
            $titlelist->SetItemChannel($item["channel_id"], $item["channel_name"]);
            $titlelist->AddItem();
        }
        db_free($rs);
    }
    $box = new Box(3);
    $box->SetTitle("最热文章 - " . $portalpage->_channel_name);
    $box->SetContent($titlelist->GetHTML(), "left", "middle", 10);

    $portalpage->AddToLeft($box);

    $portalpage->AddAdsBox(GetSettingValue("ad_1"), TRUE);

    //control buttons
    $ctlbtntpl = GetTemplate("content_controlbar_item");
    $ctlbtns = "";
    //comment button
    if (GetSettingValue("guestbook_visible"))
        $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
            "Link" => "guestbook.php?mode=add&reply=re:" . urlencode("文章《" . $portalpage->_content_name . "》"),
            "Target" => " target=\"_blank\"",
            "Image" => GetResPath("button/reply.gif", "images", GetSettingValue("style_id")),
            "Text" => "发表评论"
                ), "ControlBar");
    //print button
    $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
        "Link" => "javascript:window.print();",
        "Target" => "",
        "Image" => GetResPath("button/print.gif", "images", GetSettingValue("style_id")),
        "Text" => "打印"
            ), "ControlBar");
    //back button
    $rs = db_query($connid, "select id from article_info where id<%d and parent_class=%d order by id DESC", array($portalpage->_content_id, $portalpage->_class_id));
    if ($rs) {
        $list = db_result($rs);
        if (count($list) > 0) {
            $item = &$list[0];
            $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
                "Link" => "article.php?id=" . $item["id"],
                "Target" => "",
                "Image" => GetResPath("button/goback.gif", "images", GetSettingValue("style_id")),
                "Text" => "上一篇"
                    ), "ControlBar");
        }
        db_free($rs);
    }
    //next button
    $rs = db_query($connid, "select id from article_info where id>%d and parent_class=%d order by id ASC", array($portalpage->_content_id, $portalpage->_class_id));
    if ($rs) {
        $list = db_result($rs);
        if (count($list) > 0) {
            $item = &$list[0];
            $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
                "Link" => "article.php?id=" . $item["id"],
                "Target" => "",
                "Image" => GetResPath("button/gonext.gif", "images", GetSettingValue("style_id")),
                "Text" => "下一篇"
                    ), "ControlBar");
        }
        db_free($rs);
    }
    //home button
    $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
        "Link" => "class.php?type=article&id=" . $portalpage->_class_id,
        "Target" => "",
        "Image" => GetResPath("button/home.gif", "images", GetSettingValue("style_id")),
        "Text" => "返回栏目"
            ), "ControlBar");
    $ctlbtns = TransformTpl("content_controlbar", array("Buttons" => $ctlbtns), "ControlBar");

    //article content
    $html = TransformTpl("article", array(
        "Name" => $portalpage->_content_name,
        "Author" => $article_author,
        "Time" => $article_time,
        "VCount" => $visitor_count,
        "Text" => $article_text,
        "ControlBar" => $ctlbtns
            ), "Article");

    $contentbox = new Box(3);
    $contentbox->SetTitle($portalpage->_content_name);
    $contentbox->SetContent($html, "center", "top", 5);

    $portalpage->AddToLeft($contentbox);


    $portalpage->ShowGuestBookBox($portalpage->_content_name);

    $portalpage->AddAdsBox(GetSettingValue("ad_2"), TRUE);
} else {
    $portalpage->ShowInfo("没有可显示的内容", "错误");
}

//right
$portalpage->ShowNoticeBoard();
$portalpage->ShowSearchBox("文章搜索", 1);
if (!$portalpage->HasError()) {
    if ($portalpage->_mode == "channel")
        $portalpage->ShowNavBox1(TRUE);
    $portalpage->ShowNavBox2(1);
}

$portalpage->AddAdsBox(GetSettingValue("ad_3"), FALSE);

$portalpage->ShowCalendarBox();
$portalpage->ShowFriendListBox();

$portalpage->AddAdsBox(GetSettingValue("ad_4"), FALSE);

$portalpage->SetTitle();
$portalpage->Show();

$portalpage->CloseDBConn();
?>
