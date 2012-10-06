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
require("modules/pages/SettingAdminPage.class.php");

$adminpage = new SettingAdminPage();

require("modules/data/style_admin.module.php");
switch (strGet("function")) {
    case "sync":
        SyncStyles();
        require("modules/boxes/Admin_StyleList.class.php");
        $adminpage->AddBox(new Admin_StyleList());
        break;
    case "setdefault":
        $e = SetDefaultStyle(strPost("id"));
        if ($e) {
            $adminpage->AddBox(new MsgBox("默认风格设置成功！", "完 成", "admin_theme.php"));
        } else {
            $adminpage->AddBox(new MsgBox("默认风格设置失败", "错 误", "admin_theme.php"));
        }
        break;
    default:
        require("modules/boxes/Admin_StyleList.class.php");
        $adminpage->AddBox(new Admin_StyleList());
}

$adminpage->SetTitle("风格");
$adminpage->Show();
