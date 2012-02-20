<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class TopBox extends Box {

    function __construct($mode, $size) {
        parent::__construct("Left", "box" . intval($size));
        if ($mode == "popular")
            $this->mode = 2;
        else
            $this->mode = 1;
    }

    public function CacheBind() {
        global $_classID;
        global $_channelID;
        if (isset($_classID)) {
            $this->_cacheCategory = "class_" . $_classID;
        } else if (isset($_channelID)) {
            $this->_cacheCategory = "channel_" . $_channelID;
        } else {
            $this->_cacheCategory = "index";
        }
        $this->_cacheExpire = GetSettingValue("cache_timeout");
        if ($this->mode == 2) {
            $this->_cacheKey = "popularlist";
            $this->_cacheRandFactor = 1;
        } else {
            $this->_cacheKey = "newlist";
            $this->_cacheRandFactor = 3;
        }
        $this->_cacheVersion = GetSettingValue("version_content");
    }

    public function DataBind() {

        $title_maxnum = GetSettingValue("toplist_maxlen");
        $title_maxlen = GetSettingValue($this->_tplName . "_title_maxlen");

        $contentlist = new ContentList();
        global $_channelID;
        global $_channelName;
        global $_channelType;
        $showchannel = FALSE;
        if (isset($_channelID)) {
            $contentlist->SetChannel($_channelID, $_channelName, $_channelType);
            $showchannel = TRUE;
        }
        global $_classID;
        global $_className;
        $showclass = FALSE;
        if (isset($_classID)) {
            $contentlist->SetClass($_classID, $_className, $_channelType);
            $showclass = TRUE;
        }
        if ($showchannel && !$showclass)
            $contentlist->SetTitleList($title_maxnum, $title_maxlen, TRUE, FALSE, TRUE, 1, FALSE, $this->mode);
        else
            $contentlist->SetTitleList($title_maxnum, $title_maxlen, TRUE, FALSE, FALSE, 1, FALSE, $this->mode);

        $contentlist->SetImageList(GetSettingValue("index_grid_maxrow"), 5, 1);
        $title = "";
        if ($this->mode == 1)
            $title.=GetLangData("new");
        else
            $title.=GetLangData("popular");
        if ($showchannel || $showclass) {
            $title.=" " . GetTypeName($_channelType, 0) . " - ";

            if ($showclass)
                $title.=$_className;
            else if ($showchannel)
                $title.=$_channelName;
        }
        $this->SetTitle($title);
        $this->SetHeight("auto");
        $this->SetContent($contentlist->GetHTML($this->mode, 0), "left", "middle", 10);
    }

}
