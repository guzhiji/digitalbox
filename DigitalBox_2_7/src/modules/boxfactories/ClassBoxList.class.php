<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class ClassBoxList extends BoxFactory {

    function __construct() {
        parent::__construct("Left");
        require_once("modules/boxes/ClassBox.class.php");
    }

    public function CacheBind() {
        $this->_cacheCategory = "channel_" . intval(strGet("id"));
        $this->_cacheKey = "classlist";
        $this->_cacheTimeout = GetSettingValue("cache_timeout");
        $this->_cacheRandFactor = 3;
        $this->_cacheVersion = GetSettingValue("version_classes");
        if ($this->_cacheVersion < GetSettingValue("version_content"))
            $this->_cacheVersion = GetSettingValue("version_content");
    }

    public function DataBind() {

        $type = GetTypeNumber(trim(strGet("type")));

        $rs = db_query("select * from class_info where parent_channel=%d order by class_name", array(strGet("id")));
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0])) {
                foreach ($list as $item) {
                    $this->AddBox(new ClassBox($item["id"], $item["class_name"], $type));
                }
            }
            db_free($rs);
        }
    }

}
