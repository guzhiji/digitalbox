<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require("modules/common.module.php");
require("modules/pages/ContentAdminPage.class.php");
require("modules/data/clipboard.module.php");

global $_classID;
$_classID = intval(strGet("class"));

$adminpage = new ContentAdminPage();

$adminpage->SetTitle();

if (strGet("function") == "edit") {
    require("modules/boxfactories/ContentEditorFactory.class.php");
    $adminpage->AddBoxFactory(new ContentEditorFactory());
} else if (strGet("function") == "beginmove") {
    $id = intval(strPost("id"));
    if ($id > 0) {
        $clipboard = new ClipBoard();
        $clipboard->cut("content", array($id, strGet("type"), ""));
    }
    require("modules/boxes/Admin_ContentList.class.php");
    $adminpage->AddBox(new Admin_ContentList());
} else if (strGet("function") == "cancelmove") {
    $clipboard = new ClipBoard();
    $clipboard->cancel("content");
    require("modules/boxes/Admin_ContentList.class.php");
    $adminpage->AddBox(new Admin_ContentList());
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $_passport;
    $admin = NULL;
    switch (strtolower(strGet("type"))) {
        case "article":
            require("modules/data/article_admin.module.php");
            $admin = new Article_Admin($_passport);
            break;
        case "picture":
            require("modules/data/picture_admin.module.php");
            $admin = new Picture_Admin($_passport);
            break;
        case "media":
            require("modules/data/media_admin.module.php");
            $admin = new Media_Admin($_passport);
            break;
        case "software":
            require("modules/data/software_admin.module.php");
            $admin = new Software_Admin($_passport);
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
            $adminpage->AddBox(new MsgBox(ErrorList($admin->error), GetLangData("error"), "back"));
        } else {
            UpdateVersion("version_content");
            switch (strGet("function")) {
                case "add":
                    $adminpage->AddBox(new MsgBox("已经添加到 {$admin->class_name}", "完成", "admin_content.php?module=content&class={$_classID}"));
                    break;
                case "save":
                    $adminpage->AddBox(new MsgBox("已经保存", "完成", "admin_content.php?module=content&class={$_classID}"));
                    break;
                case "recycle":
                    $adminpage->AddBox(new MsgBox("已经被移入回收站", "完成", "admin_content.php?module=content&class={$_classID}"));
                    break;
                case "move":
                    $adminpage->AddBox(new MsgBox("已经移至 {$admin->new_parent_name}", "完成", "admin_content.php?module=content&class={$_classID}"));
                    break;
                case "delete":
                    $adminpage->AddBox(new MsgBox("已经删除", "完成", "admin_recyclebin.php?type=" . GetTypeNumber(strGet("type"))));
                    break;
            }
        }
    } else {
        $adminpage->AddBox(new MsgBox("没有此类型的内容", GetLangData("error"), "back"));
    }
} else {
    require("modules/boxes/Admin_ContentList.class.php");
    $adminpage->AddBox(new Admin_ContentList());
}

$adminpage->Show();
