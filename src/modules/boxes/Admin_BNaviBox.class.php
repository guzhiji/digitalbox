<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_BNaviBox extends Box {

    function __construct() {
        parent::__construct("BottomNaviBar", "");
    }

    public function DataBind() {

        $nb = new Navigator("navilink");

        if (GetSettingValue("search_visible"))
            $nb->AddItem(GetLangData("searchpage"), "search.php", "", FALSE, FALSE, "_blank");

        if (GetSettingValue("guestbook_visible"))
            $nb->AddItem(GetLangData("guestbook"), "guestbook.php", "", FALSE, FALSE, "_blank");

        $nb->AddItem(GetLangData("contact"), "mailto:" . GetSettingValue("master_mail"));

        if (GetSettingValue("style_changeable"))
            $nb->AddItem(GetLangData("changetheme"), "theme.php");

        $nb->AddItem(GetLangData("changelang"), "lang.php");

        if (GetSettingValue("friendsite_visible"))
            $nb->AddItem(GetLangData("friendsites"), "friendsite.php", "", FALSE, FALSE, "_blank");

        $nb->AddItem(GetLangData("logout"), "login.php?function=logout");

        $this->SetContent($nb->GetHTML(), "center", "middle", 0);
    }

}
