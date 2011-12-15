<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/channel_admin.module.php
  ------------------------------------------------------------------
 */

function ShowChannelList(&$connid, AdminPage &$adminpage) {

//    $adminpage->AddJS("function isselected(theForm){for (var i=0; i<theForm.elements.length; i++){var e = theForm.elements[i];if (e.checked) return true; }return false;}");
//    $adminpage->AddJS("function add_channel(){window.location=\"admin_content.php?module=content&function=edit\";}");
//    $adminpage->AddJS("function amend_channel(){if (isselected(document.admin_channel)){document.admin_channel.method=\"post\";document.admin_channel.action=\"admin_content.php?module=content&function=edit\";document.admin_channel.submit();}else window.alert(\"您未选择对象！\");}");
//    $adminpage->AddJS("function delete_channel(){if (isselected(document.admin_channel)){if (window.confirm(\"您真的要删除此频道吗？\")){document.admin_channel.method=\"post\";document.admin_channel.action=\"admin_content.php?module=content&function=delete\";document.admin_channel.submit();}}else window.alert(\"您未选择对象！\");}");

    $cl = new ListView("channellist_item");
    $cl->SetContainer("channellist");

    $rs = db_query($connid, "SELECT * FROM channel_info");
    if ($rs) {
        $list = db_result($rs);
        foreach ($list as $item) {
            if ($item["channel_type"] == 0) {
                $open_page = "<a title=\"{$item["channel_add"]}\" href=\"{$item["channel_add"]}\" target=\"_blank\">" . Len_Control($item["channel_name"], 30) . "</a>";
            } else {
                $open_page = "<a title=\"{$item["channel_name"]}\" href=\"admin_content.php?channel={$item["id"]}\">" . Len_Control($item["channel_name"], 30) . "</a>";
            }
            $cl->AddItem(array(
                "ID" => $item["id"],
                "Link" => $open_page,
                "Type" => GetTypeName($item["channel_type"], 0)
            ));
        }
        db_free($rs);
    }

    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("频道管理");
    $box->SetContent($cl->GetHTML(), "center", "middle", 30);
    $adminpage->AddToLeft($box);
}

function ShowChannelEditor(&$connid, AdminPage &$adminpage) {
    $types = array("自定义链接", "文章", "图片", "媒体", "软件");
    $box = new Box(3);
    $box->SetHeight("auto");
    $html = "";
    //$channelid = strPost("id");
    $channelid = strGet("id");
    if ($channelid != "") {

        $box->SetTitle("编辑频道");

        $rs = db_query($connid, "SELECT * FROM channel_info WHERE id=%d", array($channelid));
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0])) {
                $options = "";
                for ($i = 0; $i < count($types); $i++) {
                    $options.="<option" . ($i == $list[0]["channel_type"] ? " selected=\"selected\"" : "") . " value=\"{$i}\">{$types[$i]}</option>";
                }
                $html = TransformTpl("channel_editor", array(
                    "Content_Function" => "save",
                    "Content_ChannelID" => $channelid,
                    "Content_ChannelName" => TextForInputBox($list[0]["channel_name"]),
                    "Content_Types" => $options,
                    "Content_AddrHidden" => ($list[0]["channel_type"] != 0 ? " style=\"display: none;\"" : ""),
                    "Content_ChannelURL" => TextForInputBox($list[0]["channel_add"])
                        ));
            }
            db_free($rs);
        }
    } else {

        $box->SetTitle("添加频道");

        $options = "";
        for ($i = 0; $i < count($types); $i++) {
            $options.="<option" . ($i == 1 ? " selected=\"selected\"" : "") . " value=\"{$i}\">{$types[$i]}</option>";
        }
        $html = TransformTpl("channel_editor", array(
            "Content_Function" => "add",
            "Content_ChannelID" => "",
            "Content_ChannelName" => "",
            "Content_Types" => $options,
            "Content_AddrHidden" => " style=\"display: none;\"",
            "Content_ChannelURL" => ""
                ));
    }

    if ($html != "") {
        $box->SetContent($html, "center", "middle", 30);
        $adminpage->AddToLeft($box);
    } else {
        $adminpage->ShowInfo("找不到此频道", "错误", "back");
    }
}

?>