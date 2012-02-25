<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_DetailSettings extends Box {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {
        $size = explode(" ", GetSizeWithUnit(GetSettingValue("upload_maxsize")));
        $units = array("Byte", "KB", "MB", "GB");
        $unitoptions = "";
        foreach ($units as $unit) {
            $unitoptions.="<option value=\"$unit\"";
            if (strtolower($size[1]) == strtolower($unit))
                $unitoptions.=" selected=\"selected\"";
            $unitoptions.=">$unit</option>";
        }
        require("modules/filters.lib.php");
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
            "Setting_RSS_MaxLen" => GetSettingValue("rss_list_maxlen"),
            "Setting_ImageIndex_MaxRow" => GetSettingValue("index_grid_maxrow"),
            "Setting_ImageDefault_MaxRow" => GetSettingValue("general_grid_maxrow"),
            "Setting_Upload_MaxSize" => $size[0],
            "Setting_Upload_SizeUnit" => $unitoptions,
            "Setting_Upload_FileTypes" => TextForInputBox(GetSettingValue("upload_filetypes")),
            "Setting_Cache_Timeout" => GetSettingValue("cache_timeout")
                ), __CLASS__);

        $this->SetHeight("auto");
        $this->SetTitle(GetLangData("detailsettings"));
        $this->SetContent($html, "center", "middle", 2);
    }

}
