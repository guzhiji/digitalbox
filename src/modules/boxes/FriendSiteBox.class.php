<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class FriendSiteBox extends BoxModel {

    private $sItemTpl;
    private $sContainerTpl;
    private $sForMore;
    private $sLength;

    function __construct($size = 1) {
        parent::__construct($size > 1 ? "Left" : "Right", "box" . intval($size));
        if ($size > 2) {
            $this->sItemTpl = "sitelist_item";
            $this->sContainerTpl = "sitelist_container";
            $this->sForMore = 2;
            $this->sLength = GetSettingValue("general_list_maxlen");
            if (!GetSettingValue("friendsite_visible")) {
                $this->_status = 1;
                $this->_error = "closed";
            }
        } else {
            $this->sItemTpl = "sitelist_item_small";
            $this->sContainerTpl = "sitelist_container_small";
            $this->sForMore = 1;
            $this->sLength = GetSettingValue("site_list_maxlen");
            if (!GetSettingValue("friendsite_visible"))
                $this->_status = 2;
        }
    }

    public function CacheBind() {
        if ($this->sForMore == 2)
            $this->_cacheCategory = "friendsite";
        else
            $this->_cacheCategory = "portalpage";

        $this->_cacheKey = "friendsites";
        $this->_cacheTimeout = -1;
        $this->_cacheVersion = GetSettingValue("version_friendsites");
        $this->_cacheRandFactor = 1;
    }

    public function DataBind() {
        require_once("modules/lists/SiteList.class.php");
        $sl = new SiteList($this->sItemTpl, "sitelist_empty");
        $sl->SetContainer($this->sContainerTpl, $this->sForMore);
        $sl->Bind($this->sLength);

        $this->SetTitle(GetLangData("friendsites"));
        $this->SetContent($sl->GetHTML(), "center", "top", 0);
    }

}
