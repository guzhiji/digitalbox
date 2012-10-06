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

switch (strGet("function")) {
    case "savesettings":
        require_once("modules/data/setting.module.php");
        $s = array(
            "ad_1" => strPost("ad1"),
            "ad_2" => strPost("ad2"),
            "ad_3" => strPost("ad3"),
            "ad_4" => strPost("ad4"),
            "version_ads" => time()
        );
        $e = "";
        if (CheckAdsSettings($s)) {
            if (!SaveSettings($s)) {
                $e = "保存中出现存在意外错误;";
            }
        } else {
            $e = "选择了不存在的广告;";
        }
        if ($e == "") {
            $adminpage->AddBox(new MsgBox("广告设置保存成功！", "完 成", "admin_ads.php"));
        } else {
            $adminpage->AddBox(new MsgBox(ErrorList($e), GetLangData("error"), "back"));
        }
        break;
    case "savecode":
        $title = "保存广告代码";
        $back = "admin_ads.php";
        $filename = "ads/" . strGet("adname") . ".tpl";
        if (is_file($filename)) {
            $fp = @fopen($filename, "w");
            if ($fp) {
                fwrite($fp, strPost("code"));
                fclose($fp);
                $adminpage->AddBox(new MsgBox("已经保存", $title, $back));
            } else {
                $adminpage->AddBox(new MsgBox("保存失败，可能是写文件权限问题", $title, $back));
            }
        } else {
            $adminpage->AddBox(new MsgBox("保存失败，指定的广告模板文件不存在", $title, $back));
        }
        break;
    case "editcode":
        require("modules/filters.lib.php");
        $adname = strPost("adname");
        $html = TransformTpl("code_editor", array(
            "Content" => TextForTextArea(GetAds($adname)),
            "URL" => "admin_ads.php?function=savecode&adname=" . $adname,
            "Back" => "window.location='admin_ads.php'"
                ));
        $box = new Box("Left", "box3");
        $box->SetHeight("auto");
        $box->SetTitle("编辑广告代码");
        $box->SetContent($html, "center", "middle", 2);
        $adminpage->AddBox($box);
        break;
    default:
        require("modules/boxes/Admin_AdsList.class.php");
        $adminpage->AddBox(new Admin_AdsList());
}

$adminpage->SetTitle("广告");
$adminpage->Show();
