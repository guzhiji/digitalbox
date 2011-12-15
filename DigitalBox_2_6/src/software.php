<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  software.php
  ------------------------------------------------------------------
 */
require("modules/view/PortalPage.class.php");
require("modules/view/Box.class.php");
require("modules/view/ContentList.class.php");
require("modules/common.module.php");

$portalpage = new PortalPage();
$connid = $portalpage->GetDBConn();



$content_id = trim(strGet("id"));

if (strGet("mode") == "download") {
    $addr = "";

    if (isset($_SERVER["HTTP_REFERER"])) {
        //add 1 to visitorcount
        db_query($connid, "UPDATE software_info SET visitor_count=visitor_count+1 WHERE id=%d", array($content_id));

        $rs = db_query($connid, "SELECT software_add FROM software_info WHERE id=%d", array($content_id));
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0])) {
                $addr = $list[0]["software_add"];
            }
            db_free($rs);
        }
    }

    if ($addr != "") {
        db_close($connid);
        PageRedirect($addr);
    } else {
        $portalpage->RaiseError();
    }
}

if (!$portalpage->HasError()) {
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
    $sql->SetContentID($content_id);
    $sql->SetOrder(1);
    $sql->SetMode(4);

    $rs = db_query($connid, $sql->GetSelect());

    if (!$rs) {
        $portalpage->RaiseError();
    } else {

        $list = db_result($rs);
        if (isset($list[0])) {
            $item = $list[0];
            $portalpage->SetChannel($item["channel_id"], $item["channel_name"]);
            $portalpage->SetClass($item["class_id"], $item["class_name"]);
            $portalpage->SetContent($content_id, $item["content_name"]);

            $software_time = $item["software_time"];
            $software_producer = $item["software_producer"];
            $software_language = $item["software_language"];
            $software_grade = $item["software_grade"];
            $software_size = $item["software_size"];
            $software_environment = $item["software_environment"];
            $software_type = $item["software_type"];
            $software_text = $item["software_text"];
            $visitor_count = $item["visitor_count"];

            $portalpage->AddKeywords($item["content_name"]);
            $portalpage->AddKeywords($item["class_name"]);
            $portalpage->AddKeywords($item["channel_name"]);
        } else {
            $portalpage->RaiseError();
        }

        db_free($rs);
    }
}
//left
if (!$portalpage->HasError()) {

    //hottest
    $title_maxlen = GetSettingValue("box3_title_maxlen");
    $title_maxnum = GetSettingValue("toplist_maxlen");

    $sql = new SQL_Content();
    $sql->SetMode(4);
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
    $box->SetTitle("最热软件 - " . $portalpage->_channel_name);
    $box->SetContent($titlelist->GetHTML(), "left", "middle", 10);

    $portalpage->AddToLeft($box);

    $portalpage->AddAdsBox(GetSettingValue("ad_1"), TRUE);

    //control buttons
    $ctlbtntpl = GetTemplate("content_controlbar_item");
    $ctlbtns = "";
    //comment button
    if (GetSettingValue("guestbook_visible"))
        $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
            "Link" => "guestbook.php?mode=add&reply=re:" . urlencode("软件《" . $portalpage->_content_name . "》"),
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
    $rs = db_query($connid, "select id from software_info where id<%d and parent_class=%d order by id DESC", array($portalpage->_content_id, $portalpage->_class_id));
    if ($rs) {
        $list = db_result($rs);
        if (count($list) > 0) {
            $item = &$list[0];
            $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
                "Link" => "software.php?id=" . $item["id"],
                "Target" => "",
                "Image" => GetResPath("button/goback.gif", "images", GetSettingValue("style_id")),
                "Text" => "上一个"
                    ), "ControlBar");
        }
        db_free($rs);
    }
    //next button
    $rs = db_query($connid, "select id from software_info where id>%d and parent_class=%d order by id ASC", array($portalpage->_content_id, $portalpage->_class_id));
    if ($rs) {
        $list = db_result($rs);
        if (count($list) > 0) {
            $item = &$list[0];
            $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
                "Link" => "software.php?id=" . $item["id"],
                "Target" => "",
                "Image" => GetResPath("button/gonext.gif", "images", GetSettingValue("style_id")),
                "Text" => "下一个"
                    ), "ControlBar");
        }
        db_free($rs);
    }
    //home button
    $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
        "Link" => "class.php?type=software&id=" . $portalpage->_class_id,
        "Target" => "",
        "Image" => GetResPath("button/home.gif", "images", GetSettingValue("style_id")),
        "Text" => "返回栏目"
            ), "ControlBar");
    $ctlbtns = TransformTpl("content_controlbar", array("Buttons" => $ctlbtns), "ControlBar");

    //software info
    $stars = "";
    for ($a = 0; $a < $software_grade; $a++)
        $stars .= "<img border=0 src=\"" . GetResPath("star.gif", "images", GetSettingValue("style_id")) . "\" />";
    $html = TransformTpl("software", array(
        "Name" => $portalpage->_content_name,
        "Producer" => $software_producer,
        "Type" => $software_type,
        "Language" => $software_language,
        "Size" => GetSizeWithUnit($software_size),
        "Environment" => $software_environment,
        "Grade" => $stars,
        "Time" => $software_time,
        "ID" => $content_id,
        "Count" => $visitor_count,
        "ControlBar" => $ctlbtns
            ), "Software");

//	$html = "<table width=520 border=0 cellspacing=0 cellpadding=10>";
//	$html .=  "<tr><td align=center><table border=0 width=550>";
//	$html .=  "<tr><td height=30 width=100 align=center>软件名称：</td><td height=30 width=175 align=left>" . $portalpage->_content_name . "</td><td height=30 width=100 align=center>软件作者：</td><td height=30 width=175 align=left>" . $software_producer . "</td></tr>";
//	$html .=  "<tr><td height=30 width=100 align=center>软件类型：</td><td height=30 width=175 align=left>" . $software_type . "</td><td height=30 width=100 align=center>软件语言：</td><td height=30 width=175 align=left>" . $software_language . "</td></tr>";
//	$html .=  "<tr><td height=30 width=100 align=center>软件大小：</td><td height=30 width=175 align=left>" . GetSizeWithUnit($software_size) . "</td><td height=30 width=100 align=center>运行环境：</td><td height=30 width=175 align=left>" . $software_environment . "</td></tr>";
//	$html .=  "<tr><td height=30 width=100 align=center>软件星级：</td><td height=30 width=175 align=left>";
//	for ($a = 0 ; $a< $software_grade ; $a++)
//	$html .=  "<img border=0 src=\"" . GetSettingValue("style_imagefolder") . "/star.gif\">";
//
//	$html .=  "</td><td height=30 width=100 align=center>更新时间：</td><td height=30 width=175 align=left>" . $software_time . "</td></tr>";
//	$html .=  "<tr><td height=30 width=100 align=center>软件下载：</td><td height=30 align=left><a target=\"_blank\" href=\"software.php?mode=download&id=" . $content_id . "\">下载</a></td><td height=30 width=100 align=center>下载次数：</td><td height=30 width=175 align=left>" . $visitor_count . "</td></tr>";
//	$html .=  "</table></td></tr>";
//	$html .=  "<tr><td align=right><table border=0 cellpadding=5><tr>";
//
//	if(GetSettingValue("guestbook_visible")) $html .=  "<td><a target=\"_blank\" href=\"guestbook.php?mode=add&reply=re:" . urlencode("软件《" . $portalpage->_content_name . "》") . "\"><img border=0 src=\"" . GetSettingValue("style_imagefolder") . "/button/reply.gif\">发表评论</a></td>";
//
//	$rs=db_query($connid,"select id from software_info where id<%d and parent_class=%d order by id DESC",array($portalpage->_content_id,$portalpage->_class_id));
//	if($rs){
//		$list=db_result($rs);
//		if(count($list)>0){
//			$item=&$list[0];
//			$html .=  "<td><a href=\"software.php?id={$item["id"]}\"><img border=0 src=\"" . GetSettingValue("style_imagefolder") . "/button/goback.gif\">上一个</a></td>";
//		}
//		db_free($rs);
//	}
//	$rs=db_query($connid,"select id from software_info where id>%d and parent_class=%d order by id ASC",array($portalpage->_content_id,$portalpage->_class_id));
//	if($rs){
//		$list=db_result($rs);
//		if(count($list)>0){
//			$item=&$list[0];
//			$html .=  "<td><a href=\"software.php?id={$item["id"]}\"><img border=0 src=\"" . GetSettingValue("style_imagefolder") . "/button/gonext.gif\">下一个</a></td>";
//		}
//		db_free($rs);
//	}
//	$html .=  "<td><a href=\"class.php?type=software&id=" . $portalpage->_class_id . "\"><img border=0 src=\"" . GetSettingValue("style_imagefolder") . "/button/home.gif\">返回栏目</a></td>";
//	$html .=  "</tr></table></td></tr>";
//	$html .=  "</table>";

    $contentbox = new Box(3);
    $contentbox->SetTitle($portalpage->_content_name);
    $contentbox->SetContent($html, "center", "top", 0);

    $portalpage->AddToLeft($contentbox);

    //software introduction
    if ($software_text != "") {
        $html = "<div style=\"word-wrap: break-word;overflow: auto;width: 520px;text-align: left;\">" . $software_text . "</div>";

        $box = new Box(3);
        $box->SetTitle("简　介");
        $box->SetContent($html, "center", "middle", 0);

        $portalpage->AddToLeft($box);
    }

    $portalpage->ShowGuestBookBox($portalpage->_content_name);

    $portalpage->AddAdsBox(GetSettingValue("ad_2"), TRUE);
} else {
    $portalpage->ShowInfo("没有可显示的内容", "错误");
}


//right
$portalpage->ShowSearchBox("软件搜索", 4);
if (!$portalpage->HasError()) {
    if ($portalpage->_mode == "channel")
        $portalpage->ShowNavBox1(TRUE);
    $portalpage->ShowNavBox2(4);
}

$portalpage->AddAdsBox(GetSettingValue("ad_3"), FALSE);

$portalpage->ShowCalendarBox();
$portalpage->ShowFriendListBox();

$portalpage->AddAdsBox(GetSettingValue("ad_4"), FALSE);

$portalpage->SetTitle();
$portalpage->Show();

$portalpage->CloseDBConn();
?>
