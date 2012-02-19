<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_VoteList extends Box {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {

        if (GetSettingValue("vote_on")) {
            $ch = "停止";
            $en = "stop";
            $disable = "disabled=\"disabled\" ";
        } else {
            $ch = "开始";
            $en = "start";
            $disable = "";
        }
        $html = TransformTpl("voteform_editor", array(
            "Items" => GetVoteList(450, TRUE),
            "en" => $en,
            "ch" => $ch,
            "disable" => $disable
                ), __CLASS__);

        $this->SetHeight("auto");
        $this->SetTitle("投 票");
        $this->SetContent($html, "center", "middle", 20);
    }

}
