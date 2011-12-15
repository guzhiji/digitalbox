<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  admin_content.php
  ------------------------------------------------------------------
 */
require("modules/view/ContentAdminPage.class.php");
require("modules/common.module.php");
require("modules/view/Box.class.php");
require("modules/data/clipboard.module.php");

$adminpage = new ContentAdminPage();
$connid = $adminpage->GetDBConn();
$passport = $adminpage->GetPassport();

switch (strGet("module")) {
    case "upload":
        PageRedirect("admin_upload.php");
        break;
    case "recycle":
        PageRedirect("admin_recyclebin.php");
        break;
}

//left
$channelid = strGet("channel");
$classid = strGet("class");
if ($classid != "") {
    //content level
    $adminpage->SetTitle();
    require("modules/view/content_admin.module.php");

    if (strGet("function") == "edit") {
        ShowContentEditor($classid, $connid, $adminpage);
    } else if (strGet("function") == "beginmove") {
        $id = intval(strPost("id"));
        if ($id > 0) {
            $clipboard = new ClipBoard();
            $clipboard->cut("content", array($id, strGet("type"), ""));
        }
        ShowContentList($classid, $connid, $adminpage);
    } else if (strGet("function") == "cancelmove") {
        $clipboard = new ClipBoard();
        $clipboard->cancel("content");
        ShowContentList($classid, $connid, $adminpage);
    } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $admin = NULL;
        switch (strtolower(strGet("type"))) {
            case "article":
                require("modules/data/article_admin.module.php");
                $admin = new Article_Admin($connid, $passport);

                break;
            case "picture":
                require("modules/data/picture_admin.module.php");
                $admin = new Picture_Admin($connid, $passport);

                break;
            case "media":
                require("modules/data/media_admin.module.php");
                $admin = new Media_Admin($connid, $passport);

                break;
            case "software":
                require("modules/data/software_admin.module.php");
                $admin = new Software_Admin($connid, $passport);

                break;
        }
        if ($admin != NULL) {
            $continue = TRUE;
            $admin->error = "";

            if (strGet("function") == "move") {
                $clipboard = new ClipBoard();
                $cnt = $clipboard->paste("content");
                if ($cnt[2] == "recycled") {
                    $admin->restore();
                    $continue = FALSE;
                }
            }

            if ($continue && $admin->check()) {
                switch (strGet("function")) {
                    case "add":
                        $admin->add();
                        break;
                    case "save":
                        $admin->save();
                        break;
                    case "recycle":
                        $admin->recycle();
                        break;
                    case "move":
                        $clipboard = new ClipBoard();
                        $clipboard->paste("content");
                        $admin->move();
                        break;
                    case "delete":
                        $admin->delete();
                        break;
                }
            }
            if ($admin->error !== "") {
                $adminpage->ShowInfo(ErrorList($admin->error), "错误", "back");
            } else {
                switch (strGet("function")) {
                    case "add":
                        $adminpage->ShowInfo("已经添加到 {$admin->class_name}", "完成", "admin_content.php?module=content&class={$classid}");
                        break;
                    case "save":
                        $adminpage->ShowInfo("已经保存", "完成", "admin_content.php?module=content&class={$classid}");
                        break;
                    case "recycle":
                        $adminpage->ShowInfo("已经被移入回收站", "完成", "admin_content.php?module=content&class={$classid}");
                        break;
                    case "move":
                        $adminpage->ShowInfo("已经移至 {$admin->new_parent_name}", "完成", "admin_content.php?module=content&class={$classid}");
                        break;
                    case "delete":
                        $adminpage->ShowInfo("已经删除", "完成", "admin_recyclebin.php?type=" . GetTypeNumber(strGet("type")));
                        break;
                }
            }
        } else {
            $adminpage->ShowInfo("没有此类型的内容", "错误", "back");
        }
    } else {
        ShowContentList($classid, $connid, $adminpage);
    }
} else if ($channelid != "") {
    //class level
    $adminpage->SetTitle("栏目管理");
    require("modules/view/class_admin.module.php");

    if (strGet("function") == "edit") {
        ShowClassEditor(intval($channelid), $connid, $adminpage);
    } else if (strGet("function") == "cancelmove") {
        $clipboard = new ClipBoard();
        $clipboard->cancel("content");
        ShowClassList(intval($channelid), $connid, $adminpage);
    } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require("modules/data/class_admin.module.php");
        $clsadmin = new Class_Admin($connid, $passport);
        $cn = "";
        if ($clsadmin->check()) {
            switch (strGet("function")) {
                case "add":
                    $cn = "已经添加到 {$clsadmin->channel_name}";
                    $clsadmin->add();
                    break;
                case "save":
                    $cn = "已经保存";
                    $clsadmin->save();
                    break;
                case "delete":
                    $cn = "已经删除";
                    $clsadmin->delete();
                    break;
            }
        }
        if ($clsadmin->error != "") {
            $adminpage->ShowInfo(ErrorList($clsadmin->error), "错误", "back");
        } else {
            $adminpage->ShowInfo($cn, "完成", "?module=content&channel={$channelid}");
        }
    } else {
        ShowClassList(intval($channelid), $connid, $adminpage);
    }
} else {
    //channel level
    $adminpage->SetTitle("频道管理");
    require("modules/view/channel_admin.module.php");

    if (strGet("function") == "edit") {
        ShowChannelEditor($connid, $adminpage);
    } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require("modules/data/channel_admin.module.php");
        $chadmin = new Channel_Admin($connid, $passport);
        $cn = "";
        if ($chadmin->check()) {
            switch (strGet("function")) {
                case "add":
                    $cn = "已经添加";
                    $chadmin->add();
                    break;
                case "save":
                    $cn = "已经保存";
                    $chadmin->save();
                    break;
                case "delete":
                    $cn = "已经删除";
                    $chadmin->delete();
                    break;
            }
        }
        if ($chadmin->error != "") {
            $adminpage->ShowInfo(ErrorList($chadmin->error), "错误", "back");
        } else {
            $adminpage->ShowInfo($cn, "完成", "?module=content");
        }
    } else {
        ShowChannelList($connid, $adminpage);
    }
}

//right
$adminpage->ShowNavBox2();
$adminpage->ShowNavBox1();

$adminpage->Show();

$adminpage->CloseDBConn();
?>