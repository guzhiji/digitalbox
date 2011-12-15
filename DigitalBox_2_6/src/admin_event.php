<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  admin_event.php
  ------------------------------------------------------------------
 */
require("modules/view/AdminPage.class.php");
require("modules/common.module.php");
require("modules/view/Box.class.php");

function ShowVoteList(&$connid, AdminPage &$adminpage) {
    $adminpage->AddJS("function isselected(theForm){for (var i=0; i<theForm.elements.length; i++){var e = theForm.elements[i];if (e.checked) return true; }return false;}");
    $adminpage->AddJS("function delete_vote(){if (isselected(document.admin_vote)){if (window.confirm(\"您真的要删除此项目吗？\")){document.admin_vote.method=\"post\";document.admin_vote.action=\"admin_event.php?module=vote&function=delete\";document.admin_vote.submit();}}else window.alert(\"您未选择对象！\");}");

    if (GetSettingValue("vote_on")) {
        $ch = "停止";
        $en = "stop";
        $disable = "disabled=\"disabled\" ";
    } else {
        $ch = "开始";
        $en = "start";
        $disable = "";
    }
    $html = TransformTpl("voteform_editor", array(
        "Items" => GetVoteList($connid, 450, TRUE),
        "en" => $en,
        "ch" => $ch,
        "disable" => $disable
            ), "VoteList");

    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("投 票");
    $box->SetContent($html, "center", "middle", 20);
    $adminpage->AddToLeft($box);
}

function ShowCommentList(&$connid, AdminPage &$adminpage) {

    $adminpage->AddJS("function isselected(theForm){for (var i=0; i<theForm.elements.length; i++){var e = theForm.elements[i];if (e.checked) return true;}return false;}");
    $adminpage->AddJS("function view_message(){if (isselected(document.admin_guestbook)){document.admin_guestbook.method=\"get\";document.admin_guestbook.target=\"_blank\";document.admin_guestbook.action=\"guestbook.php\";document.admin_guestbook.submit();}else window.alert(\"您未选择对象！\");}");
    $adminpage->AddJS("function delete_message(){if (isselected(document.admin_guestbook)){if (window.confirm(\"您真的要删除此条留言吗？\")){document.admin_guestbook.method=\"post\";document.admin_guestbook.target=\"\";document.admin_guestbook.action=\"admin_event.php?module=comment&function=delete\";document.admin_guestbook.submit();}}else window.alert(\"您未选择对象！\");}");
    $adminpage->AddJS("function clear_guestbook(){if (window.confirm(\"您真的要清空留言本吗？留言本内容不会被备份！\")){document.admin_guestbook.method=\"post\";document.admin_guestbook.target=\"\";document.admin_guestbook.action=\"admin_event.php?module=comment&function=clear\";document.admin_guestbook.submit();}}");
    require_once("modules/view/CommentList.class.php");
    $cl = new CommentList("commentlist_admin_item");
    $cl->SetContainer("commentlist_admin", 2);
    $cl->Bind($connid);

    $box = new Box(3);
    $box->SetTitle("评论管理");
    $box->SetContent($cl->GetHTML(), "left", "middle", 10);
    $adminpage->AddToLeft($box);
}

function ShowNoticeEditor(&$adminpage) {
    $adminpage->AddJS("function edit_notice(com){document.admin_notice.action=\"admin_event.php?module=notice&function=\" + com;document.admin_notice.submit();}");
    $switch_en = "open";
    $switch_cn = "显示公告";
    if (GetSettingValue("notice_visible")) {
        $switch_en = "close";
        $switch_cn = "关闭公告";
    }
    $html = TransformTpl("event_notice", array(
        "Event_Notice_Text" => TextForTextArea(GetSettingValue("notice_text")),
        "Event_Notice_Switch_En" => $switch_en,
        "Event_Notice_Switch_Cn" => $switch_cn
            ));
    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("公 告");
    $box->SetContent($html, "center", "middle", 2);
    $adminpage->AddToLeft($box);
}

$adminpage = new AdminPage();
$connid = $adminpage->GetDBConn();
$passport = $adminpage->GetPassport();

$menu = array("公 告" => array("admin_event.php?module=notice", FALSE),
    "投 票" => array("admin_event.php?module=vote", FALSE),
    "评 论" => array("admin_event.php?module=comment", FALSE));

//left

switch (strGet("module")) {
    case "notice":
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require("modules/data/setting.module.php");
            $s = NULL;
            switch (strGet("function")) {
                case "open":
                    $s = array(
                        "notice_visible" => "true",
                        "notice_text" => Text2HTML(strPost("text"), TRUE)
                    );

                    break;
                case "close":
                    $s = array("notice_visible" => "false");

                    break;
                case "save":
                    $s = array(
                        "notice_text" => Text2HTML(strPost("text"), TRUE)
                    );

                    break;
            }
            if ($s != NULL) {
                SaveSettings($connid, $s);
                //RefreshSettings($connid);
            }
        }
        ShowNoticeEditor($adminpage);

        break;
    case "vote":
        require("modules/view/VoteList.module.php");
        require("modules/data/vote_admin.module.php");
        require("modules/data/setting.module.php");
        $voteadmin = new Vote_Admin($connid, $passport);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($voteadmin->check()) {
                switch (strGet("function")) {
                    case "set":
                        $voteadmin->set();
                        //RefreshSettings($connid);
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
                ShowVoteList($connid, $adminpage);
            } else {
                $adminpage->ShowInfo(ErrorList($voteadmin->error), "错误", "back");
            }
        } else {
            switch (strGet("function")) {
                case "set":
                    $html = TransformTpl("event_setvotedesc", array(
                        "Event_Vote_Desc" => TextForInputBox(GetSettingValue("vote_description"))
                            ));
                    $box = new Box(3);
                    $box->SetHeight("auto");
                    $box->SetTitle("设置投票描述");
                    $box->SetContent($html, "center", "middle", 2);
                    $adminpage->AddToLeft($box);
                    break;
                case "add":
                    $html = GetTemplate("event_addvoteitem");
                    $box = new Box(3);
                    $box->SetHeight("auto");
                    $box->SetTitle("添加投票项目");
                    $box->SetContent($html, "center", "middle", 2);
                    $adminpage->AddToLeft($box);
                    break;
                case "start":
                    if ($voteadmin->check() && $voteadmin->start())
                        ShowVoteList($connid, $adminpage);
                    else
                        $adminpage->ShowInfo(ErrorList($voteadmin->error), "错误", "admin_event.php?module=vote");
                    break;
                case "stop":
                    if ($voteadmin->check() && $voteadmin->stop())
                        ShowVoteList($connid, $adminpage);
                    else
                        $adminpage->ShowInfo(ErrorList($voteadmin->error), "错误", "admin_event.php?module=vote");
                    break;
                default:
                    ShowVoteList($connid, $adminpage);
            }
        }
        break;
    case "comment":
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require("modules/data/comment_admin.module.php");
            $ca = new Comment_Admin($connid, $passport);
            if ($ca->check()) {
                switch (strGet("function")) {
                    case "delete":
                        $ca->delete();
                        break;
                    case "clear":
                        $ca->clear();
                        break;
                }
                ShowCommentList($connid, $adminpage);
            } else {
                $adminpage->ShowInfo(ErrorList($ca->error), "错误", "back");
            }
        } else {
            ShowCommentList($connid, $adminpage);
        }
        break;
    default:
        $html = GetTemplate("event_home");
        $box = new Box(3);
        $box->SetHeight("auto");
        $box->SetTitle("活动管理");
        $box->SetContent($html, "left", "middle", 20);
        $adminpage->AddToLeft($box);
}
//right
$adminpage->ShowNavBox2("活动管理", $menu);
$adminpage->ShowNavBox1();

$adminpage->SetTitle("活动管理");
$adminpage->Show();

$adminpage->CloseDBConn();
?>