<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class ClassNaviBox extends Box {

    protected $_channel_id;
    protected $_channel_name;
    protected $_channel_type;
    protected $_backbutton;

    function __construct($id, $name, $type, $back = FALSE) {
        parent::__construct("Right", "box1");
        $this->_channel_id = intval($id);
        $this->_channel_name = $name;
        $this->_channel_type = $type;
        $this->_backbutton = $back;
    }

    public function CacheBind() {
        $this->_cacheCategory = "channel_" . $this->_channel_id;
        if ($this->_backbutton)
            $this->_cacheKey = "classnavi_back";
        else
            $this->_cacheKey = "classnavi";
        $this->_cacheExpire = -1;
        $this->_cacheVersion = GetSettingValue("version_classes");
    }

    public function DataBind() {

        $nb = new Navigator("navibutton");

        $zh_type = GetTypeName($this->_channel_type, 0);
        $en_type = GetTypeName($this->_channel_type, 1);

        $rs = db_query("select * from class_info where parent_channel=%d order by class_name", array($this->_channel_id));
        if ($rs) {
            $list = db_result($rs);
            foreach ($list as $item) {
                $nb->AddItem(
                        $item["class_name"], "class.php?type={$en_type}&id=" . $item["id"], ClassTip($item["class_name"], $zh_type, $this->_channel_name), FALSE, TRUE, ""
                );
            }
            db_free($rs);
        }

        if ($this->_backbutton)
            $nb->AddItem(
                    GetLangData("backtochannel"), "channel.php?type={$en_type}&id=" . $this->_channel_id, ChannelTip($this->_channel_name, $zh_type), FALSE, TRUE, ""
            );

        $this->SetHeight("auto");
        $this->SetTitle(GetLangData("naviclass"));
        $this->SetContent($nb->GetHTML(), "center", "middle", 0);
    }

}
