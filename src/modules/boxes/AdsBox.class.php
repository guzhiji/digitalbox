<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class AdsBox extends BoxModel {

    function __construct($adname, $isleft) {
        parent::__construct($isleft ? "Left" : "Right", $isleft ? "box3" : "box1");
        $this->adname = GetSettingValue($adname);
        if (empty($this->adname))
            $this->_status = 2;
    }

    public function CacheBind() {

        $this->_cacheCategory = "portalpage";
        $this->_cacheKey = "ad_" . $this->adname;
        $this->_cacheTimeout = -1;
        $this->_cacheVersion = GetSettingValue("version_ads");
        $this->_cacheRandFactor = 1;
    }

    public function DataBind() {
        $this->SetTitle(GetLangData("ads"));
        $this->SetContent(GetAds($this->adname), "center", "middle", 10);
    }

}
