<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class ContentListBox extends BoxModel {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function CacheBind() {
        $page = intval(strGet("page"));
        if ($page == 0)
            $page = 1;
        global $_classID;
        if (isset($_classID) && $page < 3) {
            $this->_cacheCategory = "class_" . $_classID;
            $this->_cacheKey = "contentlist_" . $page;
            $this->_cacheTimeout = GetSettingValue("cache_timeout");
            $this->_cacheVersion = GetSettingValue("version_content");
        } else {
            $this->_cacheCategory = "";
            $this->_cacheKey = "";
            $this->_cacheTimeout = 0;
            $this->_cacheVersion = 0;
        }
        $this->_cacheRandFactor = 1;
    }

    public function DataBind() {
        global $_error;
        if (!$_error) {
            global $_channelType;
            global $_classID;
            global $_className;

            $contentlist = new ContentList();

            $title_maxnum = GetSettingValue("general_list_maxlen");
            $image_maxrow = GetSettingValue("general_grid_maxrow");
            $contentlist = new ContentList();
            $contentlist->SetTitleList($title_maxnum, GetSettingValue("box3_title_maxlen"), TRUE, FALSE, FALSE, 1);
            $contentlist->SetImageList($image_maxrow, 5, 1);
            $contentlist->SetClass($_classID, $_className, $_channelType);
            $this->SetTitle($_className);
            $this->SetContent($contentlist->GetHTML(1, 2), "left", "top", 5);
        }
    }

}
