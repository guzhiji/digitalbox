<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  media.php
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
$sql->AddField("media_info.media_add");
$sql->AddField("media_info.media_text");
$sql->SetContentID($id);
$sql->SetOrder(1);
$sql->SetMode(3);

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

        $media_time = $item["content_time"];
        $media_add = $item["media_add"];
        $media_text = $item["media_text"];
        $visitor_count = $item["visitor_count"];

        $portalpage->AddKeywords($item["content_name"]);
        $portalpage->AddKeywords($item["class_name"]);
        $portalpage->AddKeywords($item["channel_name"]);
        
        //add 1 to visitorcount
        db_query($connid, "UPDATE media_info SET visitor_count=visitor_count+1 WHERE id=%d", array($id));
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
    $sql->SetMode(3);
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
    $box->SetTitle("最热媒体 - " . $portalpage->_channel_name);
    $box->SetContent($titlelist->GetHTML(), "left", "middle", 10);

    $portalpage->AddToLeft($box);

    $portalpage->AddAdsBox(GetSettingValue("ad_1"), TRUE);

    //media info
    $html = TransformTpl("media_info", array(
        "Name" => $portalpage->_content_name,
        "Time" => $media_time,
        "VCount" => $visitor_count,
        "Text" => $media_text
            ), "Media");

    $box = new Box(3);
    $box->SetTitle($portalpage->_content_name);
    $box->SetContent($html, "center", "middle", 0);

    $portalpage->AddToLeft($box);

    //control buttons
    $ctlbtntpl = GetTemplate("content_controlbar_item");
    $ctlbtns = "";
    //comment button
    if (GetSettingValue("guestbook_visible"))
        $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
            "Link" => "guestbook.php?mode=add&reply=re:" . urlencode("媒体《" . $portalpage->_content_name . "》"),
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
    $rs = db_query($connid, "select id from media_info where id<%d and parent_class=%d order by id DESC", array($portalpage->_content_id, $portalpage->_class_id));
    if ($rs) {
        $list = db_result($rs);
        if (count($list) > 0) {
            $item = &$list[0];
            $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
                "Link" => "media.php?id=" . $item["id"],
                "Target" => "",
                "Image" => GetResPath("button/goback.gif", "images", GetSettingValue("style_id")),
                "Text" => "上一个"
                    ), "ControlBar");
        }
        db_free($rs);
    }
    //next button
    $rs = db_query($connid, "select id from media_info where id>%d and parent_class=%d order by id ASC", array($portalpage->_content_id, $portalpage->_class_id));
    if ($rs) {
        $list = db_result($rs);
        if (count($list) > 0) {
            $item = &$list[0];
            $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
                "Link" => "media.php?id=" . $item["id"],
                "Target" => "",
                "Image" => GetResPath("button/gonext.gif", "images", GetSettingValue("style_id")),
                "Text" => "下一个"
                    ), "ControlBar");
        }
        db_free($rs);
    }
    //home button
    $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
        "Link" => "class.php?type=media&id=" . $portalpage->_class_id,
        "Target" => "",
        "Image" => GetResPath("button/home.gif", "images", GetSettingValue("style_id")),
        "Text" => "返回栏目"
            ), "ControlBar");
    $ctlbtns = TransformTpl("content_controlbar", array("Buttons" => $ctlbtns), "ControlBar");

    //media content
    $w = 500;
    $h = 300;
    $tplname = "";
    $player = "";

    switch (substr($media_add, 0, 3)) {
        case "wp:":
            //windows media player
            $tplname = "media_wp";
            $media_add = substr($media_add, 3);
            break;
        case "rp:":
            //real player
            $tplname = "media_rp";
            $media_add = substr($media_add, 3);
            break;
        case "fp:":
            //flash
            $tplname = "media_fp";
            $media_add = substr($media_add, 3);
            break;
        case "if:":
            //iframe
            $tplname = "media_if";
            $media_add = substr($media_add, 3);
            break;
        default:
            //auto
            switch (strtolower(GetFileExt($media_add))) {
                case "wmv":
                case "wma":
                case "mpg":
                case "asf":
                case "mp3":
                case "mpeg":
                case "avi":
                    //windows media player
                    $tplname = "media_wp";
                    break;
                case "rm":
                case "ra":
                case "ram":
                    //real player
                    $tplname = "media_rp";
                    break;
                case "swf":
                    //flash
                    $tplname = "media_fp";
                    break;
            }
    }

    if ($tplname != "") {
        $player = TransformTpl($tplname, array(
            "Address" => $media_add,
            "Width" => $w,
            "Height" => $h
                ), "Media");
    } else {
        $player = "未知文件格式，无法播放（<a href=\"" . urlencode($media_add) . "\" target=\"_blank\">下载文件</a>）";
    }

    $html = TransformTpl("media", array(
        "Player" => $player,
        "ControlBar" => $ctlbtns
            ), "Media");

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
$portalpage->ShowSearchBox("媒体搜索", 3);
if (!$portalpage->HasError()) {
    if ($portalpage->_mode == "channel")
        $portalpage->ShowNavBox1(TRUE);
    $portalpage->ShowNavBox2(3);
}

$portalpage->AddAdsBox(GetSettingValue("ad_3"), FALSE);

$portalpage->ShowCalendarBox();
$portalpage->ShowFriendListBox();

$portalpage->AddAdsBox(GetSettingValue("ad_4"), FALSE);

$portalpage->SetTitle();
$portalpage->Show();

$portalpage->CloseDBConn();
?>
