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


$type = strGet("type");
if ($type == "")
    $type = strPost("type");
$type = intval($type);
if ($type > 4 || $type < 1)
    $type = 1;

$adminpage = new ContentAdminPage();

switch (strGet("function")) {
    case "restore":
        $clipboard = new ClipBoard();
        $clipboard->cut("content", array(strPost("id"), GetTypeName($type, 1), "recycled"));
        $adminpage->AddBox(new MsgBox("点击“确定”选择目标栏目", "准备还原", "admin_channel.php"));
        break;
    case "clear":
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            db_query("DELETE FROM " . GetTypeName($type, 1) . "_info WHERE parent_class<1");
            $adminpage->AddBox(new MsgBox("回收站中" . GetTypeName($type, 0) . "已被清空", "完成", "admin_recyclebin.php?type=" . $type));
        } else {
            require("modules/boxes/Admin_ClearRecyclebin.class.php");
            $adminpage->AddBox(new Admin_ClearRecyclebin($type));
        }
        break;
    default:
        require("modules/boxes/Admin_RecycledList.class.php");
        $adminpage->AddBox(new Admin_RecycledList($type));
        break;
}

$adminpage->SetTitle("回收站");
$adminpage->Show();
