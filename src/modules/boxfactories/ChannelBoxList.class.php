<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class ChannelBoxList extends BoxFactory {

    function __construct() {
        parent::__construct("Left");
        require_once("modules/boxes/ChannelBox.class.php");
    }

    public function CacheBind() {
        $this->_cacheCategory = "index";
        $this->_cacheKey = "channelboxlist";
        $this->_cacheTimeout = GetSettingValue("cache_timeout");
        $this->_cacheRandFactor = 2;
        $this->_cacheVersion = GetSettingValue("version_channels");
        if ($this->_cacheVersion < GetSettingValue("version_content"))
            $this->_cacheVersion = GetSettingValue("version_content");
    }

    public function DataBind() {

        $rs = db_query("select id,channel_name,channel_type from channel_info where channel_type!=0 order by channel_type");
        if ($rs) {
            $list = db_result($rs);
            foreach ($list as $item) {
                $cb = new ChannelBox($item["id"], $item["channel_name"], $item["channel_type"]);
                $this->AddBox($cb);
            }
            db_free($rs);
        }
    }

}
