<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/class_admin.module.php
  ------------------------------------------------------------------
 */

function ShowClassList($channelid, &$connid, AdminPage &$adminpage) {

    $channelname = "";
    $rs = db_query($connid, "SELECT channel_name FROM channel_info WHERE id=%d", array($channelid));
    if ($rs) {
        $list = db_result($rs);
        if (isset($list[0])) {
            $channelname = $list[0]["channel_name"];
        }
        db_free($rs);
    }

    $cl = new ListView("classlist_item");

    $rs = db_query($connid, "SELECT * FROM class_info WHERE parent_channel=%d", array($channelid));
    if ($rs) {
        $list = db_result($rs);
        foreach ($list as $item) {
            $cl->AddItem(array(
                "ID" => $item["id"],
                "Name" => Len_Control($item["class_name"], 30)
            ));
        }
        db_free($rs);
    }

//    $adminpage->AddJS("function isselected(theForm){for (var i=0; i<theForm.elements.length; i++){ var e = theForm.elements[i];if (e.checked) return true;}return false;}");

    $clipboard = new ClipBoard();
    if ($clipboard->isCut("content")) {

        $c = $clipboard->getValue("content");

        $cl->SetContainer("classlist", array(
            "Buttons" => TransformTpl("classlist_btn_move", array(
                "channelid" => $channelid,
                "type" => $c[1],
                "contentid" => $c[0]
            ))
        ));
    } else {

        $cl->SetContainer("classlist", array(
            "Buttons" => TransformTpl("classlist_btn", array(
                "channelid" => $channelid
            ))
        ));
    }

    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("栏目管理（频道：{$channelname}）");
    $box->SetContent($cl->GetHTML(), "center", "middle", 30);
    $adminpage->AddToLeft($box);
}

function ShowClassEditor($channelid, &$connid, AdminPage &$adminpage) {
    $html = "";
    $box = new Box(3);
    $box->SetHeight("auto");
    //$classid = strPost("id");
    $classid = strGet("id");
    if ($classid != "") {
        $box->SetTitle("编辑栏目");

        $rs = db_query($connid, "SELECT class_info.class_name,channel_info.channel_type,channel_info.id AS channel_id,channel_info.channel_name FROM class_info,channel_info WHERE class_info.parent_channel=channel_info.id AND class_info.id=%d", array($classid));
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0])) {
                $html = TransformTpl("class_editor", array(
                    "Content_Function" => "save",
                    "Content_ClassID" => $classid,
                    "Content_ChannelName" => $list[0]["channel_name"],
                    "Content_ChannelID" => $list[0]["channel_id"],
                    "Content_Type" => GetTypeName($list[0]["channel_type"], 0),
                    "Content_ClassName" => TextForInputBox($list[0]["class_name"])
                        ));
            }
            db_free($rs);
        }
    } else {
        $box->SetTitle("添加栏目");

        $rs = db_query($connid, "SELECT * FROM channel_info WHERE id=%d", array($channelid));
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0])) {
                $html = TransformTpl("class_editor", array(
                    "Content_Function" => "add",
                    "Content_ClassID" => "",
                    "Content_ChannelName" => $list[0]["channel_name"],
                    "Content_ChannelID" => $channelid,
                    "Content_Type" => GetTypeName($list[0]["channel_type"], 0),
                    "Content_ClassName" => ""
                        ));
            }
            db_free($rs);
        }
    }
    if ($html != "") {
        $box->SetContent($html, "center", "middle", 30);
        $adminpage->AddToLeft($box);
    } else {
        $adminpage->ShowInfo("找不到此频道", "错误", "back");
    }
}

?>