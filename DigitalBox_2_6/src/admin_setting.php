<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  admin_setting.php
  ------------------------------------------------------------------
 */
require("modules/view/AdminPage.class.php");
require("modules/common.module.php");
require("modules/view/Box.class.php");

function ShowBasicModule(AdminPage &$adminpage) {
    $html = TransformTpl("setting_basic", array(
        "Setting_SiteName" => TextForInputBox(GetSettingValue("site_name")),
        "Setting_SiteKeywords" => TextForInputBox(GetSettingValue("site_keywords")),
        "Setting_MasterMail" => TextForInputBox(GetSettingValue("master_mail")),
        "Setting_SiteStatement" => HTMLForInputBox(GetSettingValue("site_statement")),
        "Setting_LogoURL" => TextForInputBox(GetSettingValue("logo_URL")),
        "Setting_LogoWidth" => GetSettingValue("logo_width"),
        "Setting_LogoHeight" => GetSettingValue("logo_height"),
        "Setting_LogoHidden" => GetSettingValue("logo_hidden") ? " checked=\"checked\"" : "",
        "Setting_BannerName" => GetSettingValue("banner_name"),
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

function ShowStyleModule(&$connid, AdminPage &$adminpage) {
    $totalcount = 0;
    $pagesize = 10;
    $defaultstyle = "";
    $defaultstyleid = GetSettingValue("default_style");
    $defaultstyletext = "未设置默认风格";
    $rs = db_query($connid, "SELECT count(*) FROM style_info");
    if ($rs) {
        $list = db_result($rs);
        if (isset($list[0])) {
            $totalcount = $list[0][0];
        }
        db_free($rs);
    }
    require_once("modules/view/ListView.class.php");
    require_once("modules/view/PagingBar.class.php");
    $stylelist = new ListView("stylelist_edit_item");
    $pb = new PagingBar();
    $pb->SetPageCount($totalcount, $pagesize);
    $pagecount = $pb->GetPageCount();
    $pagenumber = $pb->GetPageNumber();
    if ($totalcount > 0) {
        $start = $pagesize * ($pagenumber - 1);
        $rs = db_query($connid, "SELECT * FROM style_info LIMIT $start,$pagesize");
        if ($rs) {
            $list = db_result($rs);
            foreach ($list as $item) {
                $stylelist->AddItem(array(
                    "ID" => $item["id"],
                    "Name" => $item["style_name"]
                ));
                if ($item["id"] == $defaultstyleid)
                    $defaultstyle = $item["style_name"];
            }
        }
        db_free($rs);

        if ($defaultstyle != "") {
            $defaultstyletext = "现使用的默认风格为：$defaultstyle";
        }
        $stylelist->SetContainer("stylelist_edit", array(
            "Default" => $defaultstyletext,
            "PagingBar" => $pb->GetHTML()
        ));
    } else {
        $stylelist->SetContainer("stylelist_edit", array(
            "Default" => $defaultstyletext,
            "PagingBar" => $pb->GetHTML()
        ));
    }
    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("风格设置");
    $box->SetContent($stylelist->GetHTML(), "center", "middle", 20);
    $adminpage->AddToLeft($box);
}

function ShowFriendSiteModule(&$connid, AdminPage &$adminpage) {
    $totalcount = 0;
    $pagesize = GetSettingValue("window3_title_maxnum");
    $rs = db_query($connid, "SELECT count(*) FROM friendsite_info");
    if ($rs) {
        $list = db_result($rs);
        if (isset($list[0])) {
            $totalcount = $list[0][0];
        }
        db_free($rs);
    }
    $adminpage->AddJS("function isselected(theForm){for (var i=0; i<theForm.elements.length; i++){var e = theForm.elements[i];if (e.checked) return true; }return false;}");
    $adminpage->AddJS("function add_site(){window.location=\"?module=friendsite&function=add\";}");
    $adminpage->AddJS("function amend_site(){var fsform=document.admin_friendsite;var addr=\"\";for(var a=0;a<fsform.elements.length;a++){var e=fsform.elements[a];if(e.type==\"radio\"&&e.checked){addr=\"admin_setting.php?module=friendsite&function=save&id=\"+e.value;break;}}if(addr==\"\"){window.alert(\"您未选择对象！\");}else{window.location=addr;}}");
    $adminpage->AddJS("function delete_site(){if (isselected(document.admin_friendsite)){if (window.confirm(\"您真的要删除此友情链接吗？\")){document.admin_friendsite.method=\"post\";document.admin_friendsite.action=\"admin_setting.php?module=friendsite&function=delete\";document.admin_friendsite.submit();}}else window.alert(\"您未选择对象！\");}");

    require_once("modules/view/SiteList.class.php");
    $sl = new SiteList("sitelist_item_editor", "sitelist_empty_editor");
    $sl->SetContainer("sitelist_container_editor", 2);
    $sl->Bind($connid);

    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("友情链接");
    $box->SetContent($sl->GetHTML(), "center", "middle", 2);
    $adminpage->AddToLeft($box);
}

//--------------------------------------------------------------------------------------
$adminpage = new AdminPage();
$connid = $adminpage->GetDBConn();
$passport = $adminpage->GetPassport();

$menu = array("基础设置" => array("admin_setting.php?module=basic", TRUE),
    "具体设置" => array("admin_setting.php?module=detail", TRUE),
    "风格设置" => array("admin_setting.php?module=style", TRUE),
    "友情链接" => array("admin_setting.php?module=friendsite", TRUE));

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
                "logo_URL" => strPost("logo_URL"),
                "logo_width" => intval(strPost("logo_width")),
                "logo_height" => intval(strPost("logo_height")),
                "banner_name" => strPost("banner_name"),
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
    case "style":
        require_once("modules/data/style_admin.module.php");
        switch (strGet("function")) {
            case "sync":
                SyncStyles($connid);
                ShowStyleModule($connid, $adminpage);
                break;
            case "setdefault":
                $e = SetDefaultStyle($connid, strPost("id"));
                if ($e) {
                    $adminpage->ShowInfo("默认风格设置成功！", "完 成", "admin_setting.php?module=style");
                } else {
                    $adminpage->ShowInfo("默认风格设置失败", "错 误", "admin_setting.php?module=style");
                }
                break;
            default:
                ShowStyleModule($connid, $adminpage);
        }
        break;
    case "friendsite":
        if (strGet("function") == "") {
            ShowFriendSiteModule($connid, $adminpage);
        } else {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                require_once("modules/data/friendsite_admin.module.php");
                $funcname = "";
                $fsa = new FriendSite_Admin($connid, $passport);
                if ($fsa->check()) {
                    switch (strGet("function")) {
                        case "add":
                            $fsa->add();
                            $funcname = "添加";
                            break;
                        case "save":
                            $fsa->save();
                            $funcname = "保存";
                            break;
                        case "delete":
                            $fsa->delete();
                            $funcname = "删除";
                            break;
                    }
                }
                if ($fsa->error == "") {
                    $adminpage->ShowInfo($funcname . "完毕", "成功", "admin_setting.php?module=friendsite");
                } else {
                    $adminpage->ShowInfo(ErrorList($fsa->error), "错误", "back");
                }
            } else {
                $data = NULL;
                switch (strGet("function")) {
                    case "save":
                        $rs = db_query($connid, "SELECT * FROM friendsite_info WHERE id=%d", array(strGet("id")));
                        if ($rs) {
                            $list = db_result($rs);
                            if (isset($list[0])) {
                                $data = array(
                                    "Setting_FSite_Function" => "save",
                                    "Setting_FSite_Name" => TextForInputBox($list[0]["site_name"]),
                                    "Setting_FSite_Add" => TextForInputBox($list[0]["site_add"]),
                                    "Setting_FSite_Logo" => TextForInputBox($list[0]["site_logo"]),
                                    "Setting_FSite_Text" => TextForInputBox($list[0]["site_text"]),
                                    "Setting_FSite_ID" => $list[0]["id"]
                                );
                            }
                        }

                        break;
                    default:
                        $data = array(
                            "Setting_FSite_Function" => "add",
                            "Setting_FSite_Name" => "",
                            "Setting_FSite_Add" => "",
                            "Setting_FSite_Logo" => "",
                            "Setting_FSite_Text" => "",
                            "Setting_FSite_ID" => ""
                        );
                        break;
                }
                if ($data != NULL) {
                    $html = TransformTpl("setting_friendsite", $data);
                    $box = new Box(3);
                    $box->SetHeight("auto");
                    $box->SetTitle("友情链接");
                    $box->SetContent($html, "center", "middle", 2);
                    $adminpage->AddToLeft($box);
                } else {
                    $adminpage->ShowInfo("找不到此链接！", "错误", "back");
                }
            }
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
$adminpage->ShowNavBox2("设置管理", $menu);
$adminpage->ShowNavBox1();

$adminpage->SetTitle("设置管理");
$adminpage->Show();

$adminpage->CloseDBConn();
?>