<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_VoteDescEditor extends Box {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {
        require("modules/filters.lib.php");
        $html = TransformTpl("event_setvotedesc", array(
            "Event_Vote_Desc" => TextForInputBox(GetSettingValue("vote_description"))
                ), __CLASS__);

        $this->SetHeight("auto");
        $this->SetTitle("设置投票描述");
        $this->SetContent($html, "center", "middle", 2);
    }

}
