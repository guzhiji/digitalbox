<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  admin_ads.php
  ------------------------------------------------------------------
 */

function GetAvailableAds($name, $selected="", $hidingoption=FALSE) {
    $html = "<select name=\"$name\">";
    if ($hidingoption)
        $html.="<option value=\"\">[隐藏]</option>";
    $d = dir("ads");
    while (FALSE !== ($ad = $d->read())) {
        if (strtolower(substr($ad, -4)) == ".tpl") {
            $ad = substr($ad, 0, -4);
            if ($ad == "topbanner")
                continue;
            $s = "";
            if ($ad == $selected)
                $s = " selected=\"selected\"";
            $html.="<option value=\"$ad\"$s>$ad</option>";
        }
    }
    $d->close();
    $html.="</select>";
    return $html;
}

function CheckAdsSettings($s) {
    foreach ($s as $filename) {
        if ($filename == "")
            continue;
        $filename = "ads/{$filename}.tpl";
        if (!is_file($filename)) {
            return FALSE;
        }
    }
    return TRUE;
}

require("modules/view/SettingAdminPage.class.php");
require("modules/view/Box.class.php");
require("modules/common.module.php");

$adminpage = new SettingAdminPage();
$connid = $adminpage->GetDBConn();
$passport = $adminpage->GetPassport();

switch (strGet("function")) {
    case "savesettings":
        require_once("modules/data/setting.module.php");
        $s = array(
            "ad_1" => strPost("ad1"),
            "ad_2" => strPost("ad2"),
            "ad_3" => strPost("ad3"),
            "ad_4" => strPost("ad4")
        );
        $e = "";
        if (CheckAdsSettings($s)) {
            if (!SaveSettings($connid, $s)) {
                $e = "保存中出现存在意外错误;";
            }
        } else {
            $e = "选择了不存在的广告;";
        }
        if ($e == "") {
            $adminpage->ShowInfo("广告设置保存成功！", "完 成", "admin_ads.php");
        } else {
            $adminpage->ShowInfo(ErrorList($e), "错 误", "back");
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
                $adminpage->ShowInfo("已经保存", $title, $back);
            } else {
                $adminpage->ShowInfo("保存失败，可能是写文件权限问题", $title, $back);
            }
        } else {
            $adminpage->ShowInfo("保存失败，指定的广告模板文件不存在", $title, $back);
        }
        break;
    case "editcode":
        $adname = strPost("adname");
        $html = TransformTpl("code_editor", array(
            "Code_Content" => TextForTextArea(GetAds($adname)),
            "Code_URL" => "admin_ads.php?function=savecode&adname=" . $adname,
            "Code_Back" => "window.location='admin_ads.php'"
                ));
        $box = new Box(3);
        $box->SetHeight("auto");
        $box->SetTitle("编辑广告代码");
        $box->SetContent($html, "center", "middle", 2);
        $adminpage->AddToLeft($box);
        break;
    default:
        $html = TransformTpl("setting_ads", array(
            "Setting_Ads" => GetAvailableAds("adname"),
            "Setting_Ad1" => GetAvailableAds("ad1", GetSettingValue("ad_1"), TRUE),
            "Setting_Ad2" => GetAvailableAds("ad2", GetSettingValue("ad_2"), TRUE),
            "Setting_Ad3" => GetAvailableAds("ad3", GetSettingValue("ad_3"), TRUE),
            "Setting_Ad4" => GetAvailableAds("ad4", GetSettingValue("ad_4"), TRUE)
                ));
        $box = new Box(3);
        $box->SetHeight("auto");
        $box->SetTitle("广告设置");
        $box->SetContent($html, "center", "middle", 2);
        $adminpage->AddToLeft($box);
}

//right
$adminpage->ShowNavBox2();
$adminpage->ShowNavBox1();

$adminpage->SetTitle("广告");
$adminpage->Show();

$adminpage->CloseDBConn();
?>
