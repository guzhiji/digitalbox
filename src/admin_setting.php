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
                "banner_height" => intval(strPost("banner_height")),
                "default_lang" => strPost("default_lang"),
                "version_basicsetting" => time()
            );
            $e = CheckBasicSettings($s);
            if ($e == "") {
                if (!SaveSettings($s)) {
                    $e = "保存中出现存在意外错误;";
                }
            }
            if ($e == "") {
                $adminpage->AddBox(new MsgBox("基础设置保存成功！", "完 成", "admin_setting.php"));
            } else {
                $adminpage->AddBox(new MsgBox(ErrorList($e), GetLangData("error"), "back"));
            }
        } else {
            //ShowBasicModule($adminpage);
            require("modules/boxes/Admin_BasicSettings.class.php");
            $adminpage->AddBox(new Admin_BasicSettings());
        }
        $adminpage->SetTitle(GetLangData("basicsettings"));
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
                "rss_list_maxlen" => intval(strPost("rss_list_maxlen")),
                "index_grid_maxrow" => intval(strPost("index_grid_maxrow")),
                "general_grid_maxrow" => intval(strPost("general_grid_maxrow")),
                "upload_maxsize" => Size2Bytes(strPost("upload_maxsize"), strPost("upload_sizeunit")),
                "upload_filetypes" => strPost("upload_filetypes"),
                "cache_timeout" => intval(strPost("cache_timeout")),
                "version_detailsetting" => time()
            );
            $e = CheckDetailSettings($s);
            if ($e == "") {
                if (!SaveSettings($s)) {
                    $e = "保存中出现存在意外错误;";
                }
            }
            if ($e == "") {
                $adminpage->AddBox(new MsgBox("具体设置保存成功！", "完 成", "admin_setting.php"));
            } else {
                $adminpage->AddBox(new MsgBox(ErrorList($e), GetLangData("error"), "back"));
            }
        } else if (strGet("function") == "clearcache") {
            require_once("modules/data/setting.module.php");
            ClearCache();
            $adminpage->AddBox(new MsgBox("缓存清理完毕", "完 成", "admin_setting.php?module=detail"));
        } else {
            require("modules/boxes/Admin_DetailSettings.class.php");
            $adminpage->AddBox(new Admin_DetailSettings());
        }
        $adminpage->SetTitle(GetLangData("detailsettings"));
        break;
    case "banner":
        $back = "admin_setting.php?module=basic";
        $title = "编辑顶端横幅代码";
        if (strGet("function") == "save") {
            $fp = @fopen("ads/topbanner.tpl", "w");
            if ($fp) {
                fwrite($fp, strPost("code"));
                fclose($fp);
                $adminpage->AddBox(new MsgBox("已经保存", $title, $back));
            } else {
                $adminpage->AddBox(new MsgBox("保存失败，可能是写文件权限问题", $title, $back));
            }
        } else {
            require("modules/filters.lib.php");
            $html = TransformTpl("code_editor", array(
                "Content" => TextForTextArea(GetAds("topbanner")),
                "URL" => "admin_setting.php?module=banner&function=save",
                "Back" => "window.location='$back'"
                    ));
            $box = new Box("Left", "box3");
            $box->SetHeight("auto");
            $box->SetTitle($title);
            $box->SetContent($html, "center", "middle", 2);
            $adminpage->AddBox($box);
        }
        $adminpage->SetTitle("横幅设置");
        break;
    default:
        $html = GetTemplate("setting_home");
        $box = new Box("Left", "box3");
        $box->SetHeight("auto");
        $box->SetTitle(GetLangData("settingadmin"));
        $box->SetContent($html, "left", "middle", 20);
        $adminpage->AddBox($box);
}
$adminpage->SetTitle();
$adminpage->Show();
