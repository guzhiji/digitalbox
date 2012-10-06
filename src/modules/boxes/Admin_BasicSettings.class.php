<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_BasicSettings extends BoxModel {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {
        $langoptions = "";
        $langlist = GetSettingValue("languages");
        $dlang = GetSettingValue("default_lang");
        foreach ($langlist as $lkey => $lvalue) {
            $langoptions.="<option value=\"{$lkey}\"";
            if ($lkey == $dlang)
                $langoptions.=" selected=\"selected\"";
            $langoptions.=">{$lvalue}</option>";
        }
        require("modules/filters.lib.php");
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
            "Setting_BannerHidden" => GetSettingValue("banner_hidden") ? " checked=\"checked\"" : "",
            "Setting_DefaultLang" => $langoptions
                ), __CLASS__);

        $this->SetHeight("auto");
        $this->SetTitle(GetLangData("basicsettings"));
        $this->SetContent($html, "center", "middle", 2);
    }

}
