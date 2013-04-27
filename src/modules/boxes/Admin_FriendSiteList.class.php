<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_FriendSiteList extends Box {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {
        require_once("modules/lists/SiteList.class.php");
        $sl = new SiteList("sitelist_item_editor", "sitelist_empty_editor");
        $sl->SetContainer("sitelist_container_editor", 2);
        $sl->Bind(GetSettingValue("general_list_maxlen"));
        $this->SetHeight("auto");
        $this->SetTitle("友情链接");
        $this->SetContent($sl->GetHTML(), "center", "middle", 2);
    }

}
