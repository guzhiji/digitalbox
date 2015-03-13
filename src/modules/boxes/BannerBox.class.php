<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class BannerBox extends Box {

    function __construct() {
        parent::__construct("Banner", "banner");
    }

    public function CacheBind() {

        $this->_cacheCategory = "portalpage";
        $this->_cacheKey = "banner";
        $this->_cacheTimeout = -1;
        $this->_cacheVersion = GetSettingValue("version_basicsetting");
        $this->_cacheRandFactor = 1;
    }

    public function DataBind() {
        $l_url = trim(GetSettingValue("logo_URL"));
        $l_width = GetSettingValue("logo_width");
        $l_height = GetSettingValue("logo_height");
        $b_name = "topbanner";
        $b_width = GetSettingValue("banner_width");
        $b_height = GetSettingValue("banner_height");
        $l_visible = !GetSettingValue("logo_hidden")
                && $l_url != ""
                && $l_width > 0
                && $l_height > 0;
        $b_visible = !GetSettingValue("banner_hidden")
                && $b_width > 0
                && $b_height > 0;
        if ($l_visible) {
            $this->_width = "auto";
            $this->_height = $l_height > $b_height ? $l_height : $b_height;
            $this->_content = TransformTpl("logobanner", array(
                "LogoBanner_Height" => $this->_height,
                "Logo_URL" => $l_url,
                "Logo_Width" => $l_width,
                "Logo_Height" => $l_height,
                "Banner_Width" => $b_width,
                "Banner_Height" => $b_height,
                "Banner" => $b_visible ? GetAds($b_name) : ""
                    ), __CLASS__, NULL, "neutral");
        } else if ($b_visible) {

            $this->_width = $b_width;
            $this->_height = $l_height > $b_height ? $l_height : $b_height;
            $this->_content = GetAds($b_name);
        } else {
            $this->_content = "";
        }
    }

}
