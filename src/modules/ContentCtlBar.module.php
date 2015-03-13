<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

function GetContentCtlBar() {
    global $_contentID;
    global $_contentName;
    global $_classID;
    global $_channelType;

    //control buttons
    $ctlbtntpl = GetTemplate("content_controlbar_item");
    $ctlbtns = "";
    //comment button
    if (GetSettingValue("guestbook_visible"))
        $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
            "Link" => "guestbook.php?mode=add&reply=re:" . urlencode(GetTypeName($_channelType, 0) . "《" . $_contentName . "》"),
            "Target" => " target=\"_blank\"",
            "Image" => GetThemeResPath("button/reply.gif", "images"),
            "Text" => GetLangData("comment")
                ));
    //print button
    $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
        "Link" => "javascript:window.print();",
        "Target" => "",
        "Image" => GetThemeResPath("button/print.gif", "images"),
        "Text" => GetLangData("print")
            ));
    //back button
    $rs = db_query("select id from " . GetTypeName($_channelType, 1) . "_info where id<%d and parent_class=%d order by id DESC", array($_contentID, $_classID));
    if ($rs) {
        $list = db_result($rs);
        if (count($list) > 0) {
            $item = &$list[0];
            $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
                "Link" => GetTypeName($_channelType, 1) . ".php?id=" . $item["id"],
                "Target" => "",
                "Image" => GetThemeResPath("button/goback.gif", "images"),
                "Text" => GetLangData("goback")
                    ));
        }
        db_free($rs);
    }
    //next button
    $rs = db_query("select id from " . GetTypeName($_channelType, 1) . "_info where id>%d and parent_class=%d order by id ASC", array($_contentID, $_classID));
    if ($rs) {
        $list = db_result($rs);
        if (count($list) > 0) {
            $item = &$list[0];
            $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
                "Link" => GetTypeName($_channelType, 1) . ".php?id=" . $item["id"],
                "Target" => "",
                "Image" => GetThemeResPath("button/gonext.gif", "images"),
                "Text" => GetLangData("gonext")
                    ));
        }
        db_free($rs);
    }
    //home button
    $ctlbtns.=Tpl2HTML($ctlbtntpl, array(
        "Link" => "class.php?type=" . GetTypeName($_channelType, 1) . "&id=" . $_classID,
        "Target" => "",
        "Image" => GetThemeResPath("button/home.gif", "images"),
        "Text" => GetLangData("backtoclass")
            ));
    return TransformTpl("content_controlbar", array("Buttons" => $ctlbtns));
}
