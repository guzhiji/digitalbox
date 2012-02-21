<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class BottomNaviBox extends Box {

    function __construct() {
        parent::__construct("BottomNaviBar", "");
    }

    public function CacheBind() {
        $this->_cacheCategory = "portalpage";
        $this->_cacheKey = "bottomnavi";
        $this->_cacheTimeout = -1;
        $this->_cacheVersion = GetSettingValue("version_detailsetting");
        $this->_cacheRandFactor = 1;
    }

    public function DataBind() {

        $nb = new Navigator("navilink");

        if (GetSettingValue("search_visible"))
            $nb->AddItem(GetLangData("searchpage"), "search.php");

        if (GetSettingValue("guestbook_visible"))
            $nb->AddItem(GetLangData("guestbook"), "guestbook.php");

        $nb->AddItem("RSS", "rss.php", "", FALSE, FALSE, "_blank");

        $nb->AddItem(GetLangData("contact"), "mailto:" . GetSettingValue("master_mail"));

        if (GetSettingValue("style_changeable"))
            $nb->AddItem(GetLangData("changetheme"), "theme.php");

        $nb->AddItem(GetLangData("changelang"), "lang.php");

        if (GetSettingValue("friendsite_visible"))
            $nb->AddItem(GetLangData("friendsites"), "friendsite.php");

        $this->SetContent($nb->GetHTML(), "center", "middle", 0);
    }

}
