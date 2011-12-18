<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  admin_setting.php
  ------------------------------------------------------------------
 */

require("modules/common.module.php");

//redirect
switch (strGet("module")) {
    case "style":
        PageRedirect("admin_style.php");
        break;
    case "friendsite":
        PageRedirect("admin_friendsite.php");
        break;
    case "meta":
        PageRedirect("admin_meta.php");
        break;
    case "script":
        PageRedirect("admin_script.php");
        break;
}

function ShowBasicModule(AdminPage &$adminpage) {
    $html = TransformTpl("setting_basic", array(
        "Setting_SiteName" => TextForInputBox(GetSettingValue("site_name")),
        "Setting_SiteKeywords" => TextForInputBox(GetSettingValue("site_keywords")),
        "Setting_MasterMail" => TextForInputBox(GetSettingValue("master_mail")),
        "Setting_SiteStatement" => HTMLForInputBox(GetSettingValue("site_statement")),
        "Setting_IconURL" => TextForInputBox(GetSettingValue("icon_URL")),
        "Setting_LogoURL" => TextForInputBox(GetSettingValue("logo_URL")),
        "Setting_LogoWidth" => GetSettingValue("logo_width"),
        "Setting_LogoHeight" => GetSettingValue("logo_height"),
        "Setting_LogoHidden" => GetSettingValue("logo_hidden") ? " checked=\"checked\"" : "",
        "Setting_BannerWidth" => GetSettingValue("banner_width"),
        "Setting_BannerHeight" => GetSettingValue("banner_height"),
        "Setting_BannerHidden" => GetSettingValue("banner_hidden") ? " checked=\"checked\"" : ""
            ));
    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("基本设置");
    $box->SetContent($html, "center", "middle", 2);
    $adminpage->AddToLeft($box);
}

function ShowDetailModule(AdminPage &$adminpage) {
    $size = explode(" ", GetSizeWithUnit(GetSettingValue("upload_maxsize")));
    $units = array("Byte", "KB", "MB", "GB");
    $unitoptions = "";
    foreach ($units as $unit) {
        $unitoptions.="<option value=\"$unit\"";
        if (strtolower($size[1]) == strtolower($unit))
            $unitoptions.=" selected=\"selected\"";
        $unitoptions.=">$unit</option>";
    }
    $html = TransformTpl("setting_detail", array(
        "Setting_GuestBook_Visible" => GetSettingValue("guestbook_visible") ? " checked=\"checked\"" : "",
        "Setting_Vote_Visible" => GetSettingValue("vote_visible") ? " checked=\"checked\"" : "",
        "Setting_Search_Visible" => GetSettingValue("search_visible") ? " checked=\"checked\"" : "",
        "Setting_Calendar_Visible" => GetSettingValue("calendar_visible") ? " checked=\"checked\"" : "",
        "Setting_FriendSite_Visible" => GetSettingValue("friendsite_visible") ? " checked=\"checked\"" : "",
        "Setting_Style_Changeable" => GetSettingValue("style_changeable") ? " checked=\"checked\"" : "",
        "Setting_B1T_MaxLen" => GetSettingValue("box1_title_maxlen"),
        "Setting_B2T_MaxLen" => GetSettingValue("box2_title_maxlen"),
        "Setting_B3T_MaxLen" => GetSettingValue("box3_title_maxlen"),
        "Setting_GTL_MaxLen" => GetSettingValue("general_list_maxlen"),
        "Setting_TTL_MaxLen" => GetSettingValue("toplist_maxlen"),
        "Setting_ChTL_MaxLen" => GetSettingValue("channel_titlelist_maxlen"),
        "Setting_ClTL_MaxLen" => GetSettingValue("class_titlelist_maxlen"),
        "Setting_FSTL_MaxLen" => GetSettingValue("site_list_maxlen"),
        "Setting_GBTL_MaxLen" => GetSettingValue("guestbook_list_maxlen"),
        "Setting_CMTL_MaxLen" => GetSettingValue("comment_list_maxlen"),
        "Setting_ImageIndex_MaxRow" => GetSettingValue("index_grid_maxrow"),
        "Setting_ImageDefault_MaxRow" => GetSettingValue("general_grid_maxrow"),
        "Setting_Upload_MaxSize" => $size[0],
        "Setting_Upload_SizeUnit" => $unitoptions,
        "Setting_Upload_FileTypes" => TextForInputBox(GetSettingValue("upload_filetypes"))
            ));
    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("具体设置");
    $box->SetContent($html, "center", "middle", 2);
    $adminpage->AddToLeft($box);
}

//--------------------------------------------------------------------------------------

require("modules/view/SettingAdminPage.class.php");
require("modules/view/Box.class.php");

$adminpage = new SettingAdminPage();
$connid = $adminpage->GetDBConn();
$passport = $adminpage->GetPassport();


//left
switch (strGet("module")) {
    case "basic":
        if (strGet("function") == "save") {
            require_once("modules/data/setting.module.php");
            $s = array(
                "site_name" => strPost("site_name"),
                "site_keywords" => strPost("site_keywords"),
                "master_mail" => strPost("master_mail"),
                "site_statement" => strPost("site_statement"),
                "logo_hidden" => strtolower(strPost("logo_hidden")) == "true" ? TRUE : FALSE,
                "banner_hidden" => strtolower(strPost("banner_hidden")) == "true" ? TRUE : FALSE,
                "icon_URL" => strPost("icon_URL"),
                "logo_URL" => strPost("logo_URL"),
                "logo_width" => intval(strPost("logo_width")),
                "logo_height" => intval(strPost("logo_height")),
                "banner_width" => intval(strPost("banner_width")),
                "banner_height" => intval(strPost("banner_height"))
            );
            $e = CheckBasicSettings($s);
            if ($e == "") {
                if (!SaveSettings($connid, $s)) {
                    $e = "保存中出现存在意外错误;";
                }
            }
            if ($e == "") {
                $adminpage->ShowInfo("基础设置保存成功！", "完 成", "admin_setting.php");
            } else {
                $adminpage->ShowInfo(ErrorList($e), "错 误", "back");
            }
        } else {
            ShowBasicModule($adminpage);
        }
        break;
    case "detail":
        if (strGet("function") == "save") {
            require_once("modules/data/setting.module.php");
            $s = array(
                "guestbook_visible" => strtolower(strPost("guestbook_visible")) == "true" ? TRUE : FALSE,
                "vote_visible" => strtolower(strPost("vote_visible")) == "true" ? TRUE : FALSE,
                "search_visible" => strtolower(strPost("search_visible")) == "true" ? TRUE : FALSE,
                "calendar_visible" => strtolower(strPost("calendar_visible")) == "true" ? TRUE : FALSE,
                "friendsite_visible" => strtolower(strPost("friendsite_visible")) == "true" ? TRUE : FALSE,
                "style_changeable" => strtolower(strPost("style_changeable")) == "true" ? TRUE : FALSE,
                "box1_title_maxlen" => intval(strPost("box1_title_maxlen")),
                "box2_title_maxlen" => intval(strPost("box2_title_maxlen")),
                "box3_title_maxlen" => intval(strPost("box3_title_maxlen")),
                "general_list_maxlen" => intval(strPost("general_list_maxlen")),
                "toplist_maxlen" => intval(strPost("toplist_maxlen")),
                "channel_titlelist_maxlen" => intval(strPost("channel_titlelist_maxlen")),
                "class_titlelist_maxlen" => intval(strPost("class_titlelist_maxlen")),
                "site_list_maxlen" => intval(strPost("site_list_maxlen")),
                "guestbook_list_maxlen" => intval(strPost("guestbook_list_maxlen")),
                "comment_list_maxlen" => intval(strPost("comment_list_maxlen")),
                "index_grid_maxrow" => intval(strPost("index_grid_maxrow")),
                "general_grid_maxrow" => intval(strPost("general_grid_maxrow")),
                "upload_maxsize" => Size2Bytes(strPost("upload_maxsize"), strPost("upload_sizeunit")),
                "upload_filetypes" => strPost("upload_filetypes")
            );
            $e = CheckDetailSettings($s);
            if ($e == "") {
                if (!SaveSettings($connid, $s)) {
                    $e = "保存中出现存在意外错误;";
                }
            }
            if ($e == "") {
                $adminpage->ShowInfo("具体设置保存成功！", "完 成", "admin_setting.php");
            } else {
                $adminpage->ShowInfo(ErrorList($e), "错 误", "back");
            }
        } else {
            ShowDetailModule($adminpage);
        }
        break;
    case "banner":
        $back = "admin_setting.php?module=basic";
        $title = "编辑顶端横幅代码";
        if (strGet("function") == "save") {
            $fp = @fopen("ads/topbanner.tpl", "w");
            if ($fp) {
                fwrite($fp, strPost("code"));
                fclose($fp);
                $adminpage->ShowInfo("已经保存", $title, $back);
            } else {
                $adminpage->ShowInfo("保存失败，可能是写文件权限问题", $title, $back);
            }
        } else {
            $html = TransformTpl("code_editor", array(
                "Code_Content" => TextForTextArea(GetAds("topbanner")),
                "Code_URL" => "admin_setting.php?module=banner&function=save",
                "Code_Back" => "window.location='$back'"
                    ));
            $box = new Box(3);
            $box->SetHeight("auto");
            $box->SetTitle($title);
            $box->SetContent($html, "center", "middle", 2);
            $adminpage->AddToLeft($box);
        }
        break;
    default:
        $html = GetTemplate("setting_home");
        $box = new Box(3);
        $box->SetHeight("auto");
        $box->SetTitle("设置管理");
        $box->SetContent($html, "left", "middle", 20);
        $adminpage->AddToLeft($box);
}
//right
$adminpage->ShowNavBox2();
$adminpage->ShowNavBox1();

$adminpage->SetTitle("设置管理");
$adminpage->Show();

$adminpage->CloseDBConn();
?>