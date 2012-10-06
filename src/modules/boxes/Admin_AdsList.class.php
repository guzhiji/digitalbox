<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_AdsList extends BoxModel {

    function __construct($args) {
        parent::__construct("Left");
        $this->_tplName = "box3";
    }

    private function GetAvailableAds($name, $selected = "", $hidingoption = FALSE) {
        $html = "<select name=\"$name\">";
        if ($hidingoption)
            $html.="<option value=\"\">[" . GetLangData("hidden") . "]</option>";
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

    public function DataBind() {

        $html = TransformTpl("setting_ads", array(
            "Setting_Ads" => $this->GetAvailableAds("adname"),
            "Setting_Ad1" => $this->GetAvailableAds("ad1", GetSettingValue("ad_1"), TRUE),
            "Setting_Ad2" => $this->GetAvailableAds("ad2", GetSettingValue("ad_2"), TRUE),
            "Setting_Ad3" => $this->GetAvailableAds("ad3", GetSettingValue("ad_3"), TRUE),
            "Setting_Ad4" => $this->GetAvailableAds("ad4", GetSettingValue("ad_4"), TRUE)
                ), __CLASS__);

        $this->SetHeight("auto");
        $this->SetTitle("广告设置");
        $this->SetContent($html, "center", "middle", 2);
    }

}
