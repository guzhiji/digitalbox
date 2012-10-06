<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_Notice extends BoxModel {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {

        $switch_en = "open";
        $switch_cn = "开启公告";
        if (GetSettingValue("notice_visible")) {
            $switch_en = "close";
            $switch_cn = "关闭公告";
        }
        require_once("modules/filters.lib.php");
        $html = TransformTpl("event_notice", array(
            "Event_Notice_Text" => TextForTextArea(GetSettingValue("notice_text")),
            "Event_Notice_Switch_En" => $switch_en,
            "Event_Notice_Switch_Cn" => $switch_cn
                ), __CLASS__);

        $this->SetHeight("auto");
        $this->SetTitle("公 告");
        $this->SetContent($html, "center", "middle", 2);
    }

}
