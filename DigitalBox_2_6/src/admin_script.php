<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  admin_script.php
  ------------------------------------------------------------------
 */
require("modules/view/SettingAdminPage.class.php");
require("modules/view/Box.class.php");
require("modules/common.module.php");

$adminpage = new SettingAdminPage();
$connid = $adminpage->GetDBConn();
$passport = $adminpage->GetPassport();

$back = "admin_script.php";
switch (strGet("function")) {
    case "add":
        $title = "添加脚本";
        if (preg_match("/^[a-z0-9_\-\. ]{1,32}$/i", strPost("name"))) {
            if (@touch("scripts/" . strPost("name") . ".js")) {
                $adminpage->ShowInfo("脚本添加完毕", $title, $back);
            } else {
                $adminpage->ShowInfo("添加失败", $title, $back);
            }
        } else {
            $adminpage->ShowInfo("名称不合法，只能由英文字母、数字和“_-.”组成，长度在1-32字符", $title, $back);
        }
        break;
    case "delete":
        $title = "删除脚本";
        if (@unlink("scripts/" . strGet("name") . ".js")) {
            $adminpage->ShowInfo("删除成功", $title, $back);
        } else {
            $adminpage->ShowInfo("删除失败", $title, $back);
        }
        break;
    case "save":
        $title = "保存脚本";
        $filename = "scripts/" . strGet("name") . ".js";
        if (is_file($filename)) {
            $fp = @fopen($filename, "w");
            if ($fp) {
                fwrite($fp, strPost("code"));
                fclose($fp);
                $adminpage->ShowInfo("已经保存", $title, $back);
            } else {
                $adminpage->ShowInfo("保存失败，可能是写文件权限问题", $title, $back);
            }
        } else {
            $adminpage->ShowInfo("保存失败，指定的脚本文件不存在", $title, $back);
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
        if (!SaveSettings($connid, $s)) {
            $e = "保存中出现存在意外错误;";
        }
        if ($e == "") {
            $adminpage->ShowInfo("脚本设置保存成功！", "完 成", $back);
        } else {
            $adminpage->ShowInfo(ErrorList($e), "错 误", $back);
        }
        break;
    case "edit":
        $title = "编辑脚本";
        $scriptname = strGet("name");
        $filename = "scripts/{$scriptname}.js";
        if (is_file($filename)) {
            $script = file_get_contents($filename);
            $html = TransformTpl("code_editor", array(
                "Code_Content" => TextForTextArea($script),
                "Code_URL" => "admin_script.php?function=save&name=" . $scriptname,
                "Code_Back" => "window.location='$back'"
                    ));
            $box = new Box(3);
            $box->SetHeight("auto");
            $box->SetTitle($title);
            $box->SetContent($html, "center", "middle", 2);
            $adminpage->AddToLeft($box);
        } else {
            $adminpage->ShowInfo("指定的脚本文件不存在", $title, $back);
        }

        break;
    default:
        $selected = split(",", GetSettingValue("portal_scripts"));
        $scriptlist = new ListView("scriptlist_item");
        $scriptlist->SetContainer("scriptlist_container");
        $d = dir("scripts");
        while (FALSE !== ($script = $d->read())) {
            if (strtolower(substr($script, -3)) == ".js") {
                $script = substr($script, 0, -3);
                $s = "";
                if (in_array($script, $selected))
                    $s = " checked=\"checked\"";
                $scriptlist->AddItem(array(
                    "ScriptName" => $script,
                    "Checked" => $s
                ));
            }
        }
        $d->close();

        $box = new Box(3);
        $box->SetHeight("auto");
        $box->SetTitle("脚本设置");
        $box->SetContent($scriptlist->GetHTML(), "center", "middle", 2);
        $adminpage->AddToLeft($box);
}

//right
$adminpage->ShowNavBox2();
$adminpage->ShowNavBox1();

$adminpage->SetTitle("脚本");
$adminpage->Show();

$adminpage->CloseDBConn();
?>
