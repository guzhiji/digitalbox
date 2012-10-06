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

if (!is_dir("scripts"))
    @mkdir("scripts");

$adminpage = new SettingAdminPage();

$back = "admin_script.php";
switch (strGet("function")) {
    case "add":
        $title = "添加脚本";
        if (preg_match("/^[a-z0-9_\-\. ]{1,32}$/i", strPost("name"))) {
            if (@touch("scripts/addons/" . strPost("name") . ".js")) {
                $adminpage->AddBox(new MsgBox("脚本添加完毕", $title, $back));
            } else {
                $adminpage->AddBox(new MsgBox("添加失败", $title, $back));
            }
        } else {
            $adminpage->AddBox(new MsgBox("名称不合法，只能由英文字母、数字和“_-.”组成，长度在1-32字符", $title, $back));
        }
        break;
    case "delete":
        $title = "删除脚本";
        if (@unlink("scripts/addons/" . strGet("name") . ".js")) {
            $adminpage->AddBox(new MsgBox("删除成功", $title, $back));
        } else {
            $adminpage->AddBox(new MsgBox("删除失败", $title, $back));
        }
        break;
    case "save":
        $title = "保存脚本";
        $filename = "scripts/addons/" . strGet("name") . ".js";
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
            $adminpage->AddBox(new MsgBox("保存失败，指定的脚本文件不存在", $title, $back));
        }
        break;
    case "set":
        require_once("modules/data/setting.module.php");
        $selected = "";
        if (isset($_POST["scripts"])) {
            foreach ($_POST["scripts"] as $script) {
                $selected.=$script . ",";
            }
        }
        if ($selected != "" && substr($selected, -1) == ",") {
            $selected = substr($selected, 0, -1);
        }
        $s = array(
            "portal_scripts" => $selected
        );
        $e = "";
        if (!SaveSettings($s)) {
            $e = "保存中出现存在意外错误;";
        }
        if ($e == "") {
            $adminpage->AddBox(new MsgBox("脚本设置保存成功！", "完 成", $back));
        } else {
            $adminpage->AddBox(new MsgBox(ErrorList($e), "错 误", $back));
        }
        break;
    case "edit":
        $title = "编辑脚本";
        $scriptname = strGet("name");
        $filename = "scripts/addons/{$scriptname}.js";
        if (is_file($filename)) {
            require("modules/filters.lib.php");
            $script = file_get_contents($filename);
            $html = TransformTpl("code_editor", array(
                "Content" => TextForTextArea($script),
                "URL" => "admin_script.php?function=save&name=" . $scriptname,
                "Back" => "window.location='$back'"
                    ));
            $box = new Box("Left", "box3");
            $box->SetHeight("auto");
            $box->SetTitle($title);
            $box->SetContent($html, "center", "middle", 2);
            $adminpage->AddBox($box);
        } else {
            $adminpage->AddBox(new MsgBox("指定的脚本文件不存在", $title, $back));
        }

        break;
    default:
        require("modules/boxes/Admin_ScriptList.class.php");
        $adminpage->AddBox(new Admin_ScriptList());
}

$adminpage->SetTitle("脚本");
$adminpage->Show();
