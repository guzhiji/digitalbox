<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/content_admin.module.php
  ------------------------------------------------------------------
 */

function ShowContentList($classid, &$connid, AdminPage &$adminpage) {

    $type = 0;
    $rs = db_query($connid, "SELECT channel_info.id AS channel_id,channel_info.channel_type,channel_info.channel_name,class_info.class_name FROM class_info,channel_info WHERE channel_info.id=class_info.parent_channel AND class_info.id=%d", array($classid));
    if ($rs) {
        $list = db_result($rs);
        if (isset($list[0])) {
            $channelid = $list[0]["channel_id"];
            $type = $list[0]["channel_type"];
            $channelname = $list[0]["channel_name"];
            $classname = $list[0]["class_name"];
        }
    }
    if ($type == 0) {
        $adminpage->ShowInfo("找不到此栏目", "错误", "back");
    } else {
        $type_en = GetTypeName($type, 1);
        $type_cn = GetTypeName($type, 0);


        require("modules/view/ContentList.class.php");
        $contentlist = new ContentList($connid, "id");
        $contentlist->SetClass($classid, $classname, $type);
        $contentlist->SetTitleList(GetSettingValue("general_list_maxlen"), GetSettingValue("box3_title_maxlen"), FALSE, FALSE, FALSE, 2);
        $contentlist->SetImageList(GetSettingValue("general_grid_maxrow"), 5, 2);

        $html = $contentlist->GetHTML(1, 2, "admin_content.php?module=content&class={$classid}");

//        $adminpage->AddJS("function isselected(theForm){for (var i=0; i<theForm.elements.length; i++){var e = theForm.elements[i];if (e.checked) return true; }return false;}");

        $clipboard = new ClipBoard();
        if ($clipboard->isCut("content")) {

            $c = $clipboard->getValue("content");
            $html = TransformTpl("contentlist_admin", array(
                "ListItems" => $html,
                "Buttons" => TransformTpl("contentlist_admin_btn_move", array(
                    "classid" => $classid,
                    "type" => $c[1],
                    "contentid" => $c[0],
                    "channelid" => $channelid
                ))
                    ));
        } else {

            $html = TransformTpl("contentlist_admin", array(
                "ListItems" => $html,
                "Buttons" => TransformTpl("contentlist_admin_btn", array(
                    "classid" => $classid,
                    "type_en" => $type_en,
                    "type_cn" => $type_cn,
                    "channelid" => $channelid
                ))
                    ));
        }

        $box = new Box(3);
        $box->SetHeight("auto");
        $box->SetTitle("内容管理（栏目：{$classname}）");
        $box->SetContent($html, "center", "middle", 30);
        $adminpage->AddToLeft($box);
    }
}

function ShowContentEditor($classid, &$connid, AdminPage &$adminpage) {
    $html = "";
    $title = "";
    $rs = db_query($connid, "SELECT class_info.id AS class_id,class_info.class_name,channel_info.channel_type FROM class_info,channel_info WHERE class_info.parent_channel=channel_info.id AND class_info.id=%d", array($classid));
    if ($rs) {
        $list = db_result($rs);
        if (isset($list[0])) {
            //$contentid = strPost("id");
            $contentid = strGet("id");
            if ($contentid != "") {
                //MODIFY CONTENT
                //prepare sql
                require_once("modules/data/sql_content.module.php");
                $sql = new SQL_Content();
                $sql->SetMode($list[0]["channel_type"]);
                $sql->SetContentID($contentid);
                switch ($list[0]["channel_type"]) {
                    case 1:
                        $sql->AddField("article_author");
                        $sql->AddField("article_text");
                        $title = "编辑文章";
                        break;
                    case 2:
                        $sql->AddField("picture_add");
                        $sql->AddField("picture_text");
                        $title = "编辑图片";
                        break;
                    case 3:
                        $sql->AddField("media_add");
                        $sql->AddField("media_text");
                        $title = "编辑媒体";
                        break;
                    case 4:
                        $sql->AddField("software_producer");
                        $sql->AddField("software_type");
                        $sql->AddField("software_language");
                        $sql->AddField("software_size");
                        $sql->AddField("software_environment");
                        $sql->AddField("software_grade");
                        $sql->AddField("software_add");
                        $sql->AddField("software_text");
                        $title = "编辑软件";
                        break;
                }
                //run sql
                $rs2 = db_query($connid, $sql->GetSelect());
                if ($rs2) {
                    $list = db_result($rs2);
                    if (isset($list[0])) {
                        //generate html
                        switch ($list[0]["channel_type"]) {
                            case 1:
                                $html = TransformTpl("article_editor", array(
                                    "Content_Function" => "save",
                                    "Content_Class" => $list[0]["class_id"],
                                    "Content_ClassName" => $list[0]["class_name"],
                                    "Content_ID" => intval($contentid),
                                    "Content_Name" => TextForInputBox($list[0]["content_name"]),
                                    "Content_Author" => TextForInputBox($list[0]["article_author"]),
                                    "Content_Text" => HTMLForTextArea($list[0]["article_text"])
                                        ));

                                break;
                            case 2:
                                $html = TransformTpl("picture_editor", array(
                                    "Content_Function" => "save",
                                    "Content_Class" => $list[0]["class_id"],
                                    "Content_ClassName" => $list[0]["class_name"],
                                    "Content_ID" => intval($contentid),
                                    "Content_Name" => TextForInputBox($list[0]["content_name"]),
                                    "Content_Addr" => TextForInputBox($list[0]["picture_add"]),
                                    "Content_Text" => HTMLForTextArea($list[0]["picture_text"])
                                        ));

                                break;
                            case 3:
                                $html = TransformTpl("media_editor", array(
                                    "Content_Function" => "save",
                                    "Content_Class" => $list[0]["class_id"],
                                    "Content_ClassName" => $list[0]["class_name"],
                                    "Content_ID" => intval($contentid),
                                    "Content_Name" => TextForInputBox($list[0]["content_name"]),
                                    "Content_Addr" => TextForInputBox($list[0]["media_add"]),
                                    "Content_Text" => HTMLForTextArea($list[0]["media_text"])
                                        ));

                                break;
                            case 4:
                                require("modules/view/software_editor.module.php");
                                $editor = new Software_Editor();
                                $editor->setFunction("save");
                                $editor->setClass($list[0]["class_id"], $list[0]["class_name"]);
                                $editor->setID($list[0]["id"]);
                                $editor->setName($list[0]["content_name"]);
                                $editor->setAddr($list[0]["software_add"]);
                                $editor->setText($list[0]["software_text"]);
                                $editor->setType($list[0]["software_type"]);
                                $editor->setProducer($list[0]["software_producer"]);
                                $editor->setGrade($list[0]["software_grade"]);
                                $editor->setLanguage($list[0]["software_language"]);
                                $editor->setSize($list[0]["software_size"]);
                                $editor->setEnv($list[0]["software_environment"]);

                                $html = $editor->getHTML();
                                break;
                        }
                    }
                    db_free($rs2);
                }
            } else {
                //CREATE NEW CONTENT
                //generate html
                switch ($list[0]["channel_type"]) {
                    case 1:
                        $html = TransformTpl("article_editor", array(
                            "Content_Function" => "add",
                            "Content_Class" => $list[0]["class_id"],
                            "Content_ClassName" => $list[0]["class_name"],
                            "Content_ID" => "",
                            "Content_Name" => "",
                            "Content_Author" => "",
                            "Content_Text" => ""
                                ));
                        $title = "添加文章";
                        break;
                    case 2:
                        $html = TransformTpl("picture_editor", array(
                            "Content_Function" => "add",
                            "Content_Class" => $list[0]["class_id"],
                            "Content_ClassName" => $list[0]["class_name"],
                            "Content_ID" => "",
                            "Content_Name" => "",
                            "Content_Addr" => dbUploadPath . "/",
                            "Content_Text" => ""
                                ));
                        $title = "添加图片";
                        break;
                    case 3:
                        $html = TransformTpl("media_editor", array(
                            "Content_Function" => "add",
                            "Content_Class" => $list[0]["class_id"],
                            "Content_ClassName" => $list[0]["class_name"],
                            "Content_ID" => "",
                            "Content_Name" => "",
                            "Content_Addr" => dbUploadPath . "/",
                            "Content_Text" => ""
                                ));
                        $title = "添加媒体";
                        break;
                    case 4:
                        require("modules/view/software_editor.module.php");
                        $editor = new Software_Editor();
                        $editor->setClass($list[0]["class_id"], $list[0]["class_name"]);
                        $html = $editor->getHTML();
                        $title = "添加软件";
                        break;
                }
            }
        }
        db_free($rs);
    }
    //insert html into page
    if ($html != "") {
        $box = new Box(3);
        $box->SetHeight("auto");
        $box->SetTitle($title);
        $box->SetContent($html, "center", "middle", 10);
        $adminpage->AddToLeft($box);
    } else {
        $adminpage->ShowInfo("找不到内容", "错误", "back");
    }
}

?>