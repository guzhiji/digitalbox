<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require("modules/common.module.php");
require("modules/pages/EventAdminPage.class.php");

function ShowCommentList(AdminPage $adminpage) {

//    $adminpage->AddJS("function isselected(theForm){for (var i=0; i<theForm.elements.length; i++){var e = theForm.elements[i];if (e.checked) return true;}return false;}");
//    $adminpage->AddJS("function view_message(){if (isselected(document.admin_guestbook)){document.admin_guestbook.method=\"get\";document.admin_guestbook.target=\"_blank\";document.admin_guestbook.action=\"guestbook.php\";document.admin_guestbook.submit();}else window.alert(\"您未选择对象！\");}");
//    $adminpage->AddJS("function delete_message(){if (isselected(document.admin_guestbook)){if (window.confirm(\"您真的要删除此条留言吗？\")){document.admin_guestbook.method=\"post\";document.admin_guestbook.target=\"\";document.admin_guestbook.action=\"admin_event.php?module=comment&function=delete\";document.admin_guestbook.submit();}}else window.alert(\"您未选择对象！\");}");
//    $adminpage->AddJS("function clear_guestbook(){if (window.confirm(\"您真的要清空留言本吗？留言本内容不会被备份！\")){document.admin_guestbook.method=\"post\";document.admin_guestbook.target=\"\";document.admin_guestbook.action=\"admin_event.php?module=comment&function=clear\";document.admin_guestbook.submit();}}");
    require_once("modules/lists/CommentList.class.php");
    $cl = new CommentList("commentlist_admin_item");
    $cl->SetContainer("commentlist_admin", 2);
    $cl->Bind();

    $box = new Box("Left", "box3");
    $box->SetTitle(GetLangData("comments"));
    $box->SetContent($cl->GetHTML(), "left", "middle", 10);
    $adminpage->AddBox($box);
}

$adminpage = new EventAdminPage();

switch (strGet("module")) {
    case "notice":
        $adminpage->SetTitle(GetLangData("notice"));
        $error = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require("modules/filters.lib.php");
            require("modules/data/setting.module.php");
            $s = NULL;
            $info = "";
            switch (strGet("function")) {
                case "open":
                    $s = array(
                        "notice_visible" => TRUE,
                        "notice_text" => Text2HTML(strPost("text"), TRUE)
                    );
                    $info = "无法开启公告";
                    break;
                case "close":
                    $s = array("notice_visible" => FALSE);
                    $info = "无法关闭公告";

                    break;
                case "save":
                    $s = array(
                        "notice_text" => Text2HTML(strPost("text"), TRUE)
                    );
                    $info = "保存中出现意外错误";

                    break;
            }
            if ($s != NULL) {
                if (!SaveSettings($s)) {
                    $error = $info . "，可能是文件系统权限限制导致，或者是数据库操作失败";
                }
            }
        }
        if ($error == "") {
            require("modules/boxes/Admin_Notice.class.php");
            $adminpage->AddBox(new Admin_Notice());
        } else {
            $adminpage->AddBox(new MsgBox(ErrorList($error), GetLangData("error"), "back"));
        }
        break;
    case "vote":
        $adminpage->SetTitle(GetLangData("vote"));
        require("modules/VoteList.module.php");
        require("modules/data/vote_admin.module.php");
        require("modules/data/setting.module.php");
        global $_passport;
        $voteadmin = new Vote_Admin($_passport);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($voteadmin->check()) {
                switch (strGet("function")) {
                    case "set":
                        $voteadmin->set();
                        break;
                    case "add":
                        $voteadmin->add();
                        break;
                    case "delete":
                        $voteadmin->delete();
                        break;
                }
            }
            if ($voteadmin->error == "") {
                UpdateVersion("version_vote");
                require("modules/boxes/Admin_VoteList.class.php");
                $adminpage->AddBox(new Admin_VoteList());
            } else {
                $adminpage->AddBox(new MsgBox(ErrorList($voteadmin->error), GetLangData("error"), "back"));
            }
        } else {
            switch (strGet("function")) {
                case "set":
                    require("modules/boxes/Admin_VoteDescEditor.class.php");
                    $adminpage->AddBox(new Admin_VoteDescEditor());
//                    $html = TransformTpl("event_setvotedesc", array(
//                        "Event_Vote_Desc" => TextForInputBox(GetSettingValue("vote_description"))
//                            ));
//                    $box = new Box("Left", "box3");
//                    $box->SetHeight("auto");
//                    $box->SetTitle("设置投票描述");
//                    $box->SetContent($html, "center", "middle", 2);
//                    $adminpage->AddBox($box);
                    break;
                case "add":
                    $html = GetTemplate("event_addvoteitem");
                    $box = new Box("Left", "box3");
                    $box->SetHeight("auto");
                    $box->SetTitle("添加投票项目");
                    $box->SetContent($html, "center", "middle", 2);
                    $adminpage->AddBox($box);
                    break;
                case "start":
                    if ($voteadmin->check() && $voteadmin->start()) {
                        UpdateVersion("version_vote");
                        require("modules/boxes/Admin_VoteList.class.php");
                        $adminpage->AddBox(new Admin_VoteList());
                    } else {
                        $adminpage->AddBox(new MsgBox(ErrorList($voteadmin->error), GetLangData("error"), "admin_event.php?module=vote"));
                    }
                    break;
                case "stop":
                    if ($voteadmin->check() && $voteadmin->stop()) {
                        UpdateVersion("version_vote");
                        require("modules/boxes/Admin_VoteList.class.php");
                        $adminpage->AddBox(new Admin_VoteList());
                    } else {
                        $adminpage->AddBox(new MsgBox(ErrorList($voteadmin->error), GetLangData("error"), "admin_event.php?module=vote"));
                    }
                    break;
                default:
                    require("modules/boxes/Admin_VoteList.class.php");
                    $adminpage->AddBox(new Admin_VoteList());
            }
        }
        break;
    case "comment":
        $adminpage->SetTitle(GetLangData("comments"));
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require("modules/data/comment_admin.module.php");
            global $_passport;
            $ca = new Comment_Admin($_passport);
            if ($ca->check()) {
                switch (strGet("function")) {
                    case "delete":
                        $ca->delete();
                        break;
                    case "clear":
                        $ca->clear();
                        break;
                }
            }
            if ($ca->error == "") {
                ShowCommentList($adminpage);
            } else {
                $adminpage->AddBox(new MsgBox(ErrorList($ca->error), GetLangData("error"), "back"));
            }
        } else {
            ShowCommentList($adminpage);
        }
        break;
    default:
        $html = GetTemplate("event_home");
        $box = new Box("Left", "box3");
        $box->SetHeight("auto");
        $box->SetTitle(GetLangData("eventadmin"));
        $box->SetContent($html, "left", "middle", 20);
        $adminpage->AddBox($box);
        $adminpage->SetTitle();
}

$adminpage->Show();
