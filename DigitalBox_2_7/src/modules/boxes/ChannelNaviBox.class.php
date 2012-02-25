<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class ChannelNaviBox extends Box {

    protected $_itemtpl;
    protected $_backbutton;
    protected $_eventdriven;

    function __construct($type, $back = FALSE) {
        parent::__construct($type, "");
        if ($type == "TopNaviBar") {
            $this->_backbutton = FALSE;
            $this->_itemtpl = "navilink";
            $this->_eventdriven = FALSE;
        } else {
            $this->_backbutton = $back;
            $this->_type = "Right";
            $this->_tplName = "box1";
            $this->_itemtpl = "navibutton";
            $this->_eventdriven = TRUE;
        }
    }

    public function CacheBind() {
        $this->_cacheVersion = GetSettingValue("version_channels");
        if ($this->_eventdriven) {
            $this->_cacheKey = "channelnavi_right";
            if ($this->_backbutton)
                $this->_cacheCategory = "portalpage";
            else
                $this->_cacheCategory = "index";
        }else {
            $this->_cacheKey = "channelnavi_top";
            $this->_cacheCategory = "portalpage";
            if ($this->_cacheVersion < GetSettingValue("version_detailsetting"))
                $this->_cacheVersion = GetSettingValue("version_detailsetting");
        }
        $this->_cacheTimeout = -1;
        $this->_cacheRandFactor = 1;
    }

    public function DataBind() {

        $eventdriven = $this->_eventdriven;
        $nb = new Navigator($this->_itemtpl);

        if (!$eventdriven) {
            $nb->AddItem(GetLangData("homepage"), "index.php");
            if (GetSettingValue("search_visible"))
                $nb->AddItem(GetLangData("searchpage"), "search.php");
            if (GetSettingValue("guestbook_visible"))
                $nb->AddItem(GetLangData("guestbook"), "guestbook.php");
        }

        $rs = db_query("select * from channel_info order by channel_name");
        if ($rs) {
            $list = db_result($rs);
            foreach ($list as $item) {
                if ($item["channel_type"] == 0) {
                    $nb->AddItem(
                            $item["channel_name"], $item["channel_add"], ChannelTip($item["channel_name"], GetTypeName($item["channel_type"], 0)), FALSE, $eventdriven, "_blank"
                    );
                } else {
                    $nb->AddItem(
                            $item["channel_name"], "channel.php?type=" . GetTypeName($item["channel_type"], 1) . "&id=" . $item["id"], ChannelTip($item["channel_name"], GetTypeName($item["channel_type"], 0)), FALSE, $eventdriven, ""
                    );
                }
            }
            db_free($rs);
        }

        if ($this->_backbutton) {
            $nb->AddItem(
                    GetLangData("back"), "index.php", "", FALSE, $eventdriven, ""
            );
        }

        $this->SetTitle(GetLangData("navichannel"));
        $this->SetContent($nb->GetHTML(), "center", "middle", 0);
    }

}
