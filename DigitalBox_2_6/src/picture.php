<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  picture.php
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
$sql->AddField("picture_info.picture_add");
$sql->AddField("picture_info.picture_text");
$sql->SetContentID($id);
$sql->SetOrder(1);
$sql->SetMode(2);

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

        $picture_time = $item["content_time"];
        $visitor_count = $item["visitor_count"];
        $picture_add = $item["picture_add"];
        $picture_text = $item["picture_text"];

        $portalpage->AddKeywords($item["content_name"]);
        $portalpage->AddKeywords($item["class_name"]);
        $portalpage->AddKeywords($item["channel_name"]);

        //add 1 to visitorcount
        db_query($connid, "UPDATE picture_info SET visitor_count=visitor_count+1 WHERE id=%d", array($id));
        $visitor_count++;
    } else {
        $portalpage->RaiseError();
    }
    db_free($rs);
}

//left
if (!$portalpage->HasError()) {

    //hottest
    $sql = new SQL_Content();
    $sql->AddField("picture_info.picture_add");
    $sql->SetMode(2);
    $sql->SetOrder(2);
    $sql->SetChannelID($portalpage->_channel_id);


    $imagelist = new ImageList();
    $imagelist->SetLinkType(1);
    $imagelist->_maxrow = GetSettingValue("index_grid_maxrow");
    $rs = db_query($connid, $sql->GetSelect() . " LIMIT 0," . $imagelist->Size());
    if ($rs) {
        $list = db_result($rs);
        foreach ($list as $item) {
            $imagelist->CreateItem();
            $imagelist->SetItemContent($item["content_id"], $item["content_name"], $item["content_time"], $item["visitor_count"], $item["picture_add"]);
            $imagelist->SetItemClass($item["class_id"], $item["class_name"]);
            $imagelist->SetItemChannel($item["channel_id"], $item["channel_name"]);
            $imagelist->AddItem();
        }
        db_free($rs);
    }
    $box = new Box(3);
    $box->SetTitle("最热图片 - " . $portalpage->_channel_name);
    $box->SetContent($imagelist->GetHTML(), "center", "middle", 5);

    $portalpage->AddToLeft($box);


    $portalpage->AddAdsBox(GetSettingValue("ad_1"), TRUE);

    //picture info
    $html = TransformTpl("picture_info", array(
        "Name" => $portalpage->_content_name,
        "Time" => $picture_time,
        "VCount" => $visitor_count,
        "Text" => $picture_text
            ), "Picture");

    $box = new Box(3);
    $box->SetTitle("图片信息");
    $box->SetContent($html, "center", "top", 0);

    $portalpage->AddToLeft($box);

    //control buttons
    $ctlbtntpl = GetTemplate("content_controlbar_item");
    $ctlbtns = "";
    //comment button
    if (GetSettingValue("guestbook_visible"))
        $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
            "Link" => "guestbook.php?mode=add&reply=re:" . urlencode("图片《" . $portalpage->_content_name . "》"),
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
    $rs = db_query($connid, "select id from picture_info where id<%d and parent_class=%d order by id DESC", array($portalpage->_content_id, $portalpage->_class_id));
    if ($rs) {
        $list = db_result($rs);
        if (count($list) > 0) {
            $item = &$list[0];
            $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
                "Link" => "picture.php?id=" . $item["id"],
                "Target" => "",
                "Image" => GetResPath("button/goback.gif", "images", GetSettingValue("style_id")),
                "Text" => "上一幅"
                    ), "ControlBar");
        }
        db_free($rs);
    }
    //next button
    $rs = db_query($connid, "select id from picture_info where id>%d and parent_class=%d order by id ASC", array($portalpage->_content_id, $portalpage->_class_id));
    if ($rs) {
        $list = db_result($rs);
        if (count($list) > 0) {
            $item = &$list[0];
            $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
                "Link" => "picture.php?id=" . $item["id"],
                "Target" => "",
                "Image" => GetResPath("button/gonext.gif", "images", GetSettingValue("style_id")),
                "Text" => "下一幅"
                    ), "ControlBar");
        }
        db_free($rs);
    }
    //home button
    $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
        "Link" => "class.php?type=picture&id=" . $portalpage->_class_id,
        "Target" => "",
        "Image" => GetResPath("button/home.gif", "images", GetSettingValue("style_id")),
        "Text" => "返回栏目"
            ), "ControlBar");
    $ctlbtns = TransformTpl("content_controlbar", array("Buttons" => $ctlbtns), "ControlBar");

    //picture content
    $html = TransformTpl("picture", array(
        "Addr" => $picture_add,
        "ControlBar" => $ctlbtns
            ), "Picture");

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
$portalpage->ShowSearchBox("图片搜索", 2);
if (!$portalpage->HasError()) {
    if ($portalpage->_mode == "channel")
        $portalpage->ShowNavBox1(TRUE);
    $portalpage->ShowNavBox2(2);
}

$portalpage->AddAdsBox(GetSettingValue("ad_3"), FALSE);

$portalpage->ShowCalendarBox();
$portalpage->ShowFriendListBox();

$portalpage->AddAdsBox(GetSettingValue("ad_4"), FALSE);

$portalpage->SetTitle();
$portalpage->Show();

$portalpage->CloseDBConn();
?>
