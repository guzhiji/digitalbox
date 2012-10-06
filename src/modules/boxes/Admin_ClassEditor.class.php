<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_ClassEditor extends BoxModel {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {

        $this->SetAlign("center", "top");

        global $_classID;
        global $_channelID;
        if (isset($_classID)) {

            $this->SetTitle(GetLangData("edit"));

            $rs = db_query("SELECT class_info.class_name,channel_info.channel_type,channel_info.id AS channel_id,channel_info.channel_name FROM class_info,channel_info WHERE class_info.parent_channel=channel_info.id AND class_info.id=%d", array($_classID));
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    require("modules/filters.lib.php");
                    $this->_content = TransformTpl("class_editor", array(
                        "Content_Function" => "save",
                        "Content_ClassID" => $_classID,
                        "Content_ChannelName" => $list[0]["channel_name"],
                        "Content_ChannelID" => $list[0]["channel_id"],
                        "Content_Type" => GetTypeName($list[0]["channel_type"], 0),
                        "Content_ClassName" => TextForInputBox($list[0]["class_name"])
                            ), __CLASS__);
                } else {
                    $this->_status = 1;
                }
                db_free($rs);
            } else {
                $this->_status = 1;
            }
            if ($this->_status == 1) {
                $this->_error = "class not found";
                //TODO error message
            }
        } else if (isset($_channelID)) {

            $this->SetTitle(GetLangData("add"));

            $rs = db_query("SELECT * FROM channel_info WHERE id=%d", array($_channelID));
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    $this->_content = TransformTpl("class_editor", array(
                        "Content_Function" => "add",
                        "Content_ClassID" => "",
                        "Content_ChannelName" => $list[0]["channel_name"],
                        "Content_ChannelID" => $_channelID,
                        "Content_Type" => GetTypeName($list[0]["channel_type"], 0),
                        "Content_ClassName" => ""
                            ), __CLASS__);
                } else {
                    $this->_status = 1;
                }
                db_free($rs);
            } else {
                $this->_status = 1;
            }
            if ($this->_status == 1) {
                $this->_error = "parent channel not found";
                //TODO error message
            }
        } else {
            $this->_status = 1;
            $this->_error = "nothing";
            //TODO error message
        }
    }

}
