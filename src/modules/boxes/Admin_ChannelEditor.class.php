<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_ChannelEditor extends Box {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {

        $this->SetAlign("center", "top");

        global $_channelID;

        if (isset($_channelID)) {

            $this->SetTitle(GetLangData("edit"));

            $rs = db_query("SELECT * FROM channel_info WHERE id=%d", array($_channelID));
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    require("modules/filters.lib.php");
                    $this->_content = TransformTpl("channel_editor", array(
                        "Content_Function" => "save",
                        "Content_ChannelID" => $_channelID,
                        "Content_ChannelName" => TextForInputBox($list[0]["channel_name"]),
                        "Content_Types" => GetTypeName($list[0]["channel_type"], 0),
                        "Content_AddrHidden" => ($list[0]["channel_type"] != 0 ? " style=\"display: none;\"" : ""),
                        "Content_ChannelURL" => TextForInputBox($list[0]["channel_add"])
                            ), __CLASS__);
                } else {
                    $this->_status = 1;
                }
                db_free($rs);
            } else {
                $this->_status = 1;
            }
            if ($this->_status == 1) {
                $this->_error = "not found";
                //TODO error message
            }
        } else {

            $this->SetTitle(GetLangData("add"));

            require "modules/HTMLSelect.class.php";
            $select = new HTMLSelect("channel_type");
            $select->SetCSSClass("select2");
            $select->SetOnChange("changeType(this.value)");
            $select->Select(1);
            for ($t = 0; $t < 5; ++$t)
                $select->AddItem($t, GetTypeName($t, 0));

            $this->_content = TransformTpl("channel_editor", array(
                "Content_Function" => "add",
                "Content_ChannelID" => "",
                "Content_ChannelName" => "",
                "Content_Types" => $select->GetHTML(),
                "Content_AddrHidden" => " style=\"display: none;\"",
                "Content_ChannelURL" => ""
                    ), __CLASS__);
        }
    }

}
