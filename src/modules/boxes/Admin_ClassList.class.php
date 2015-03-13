<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_ClassList extends Box {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {

        global $_channelID;
        if (isset($_channelID)) {

            global $_channelName;
            $_channelName = "";
            $rs = db_query("SELECT channel_name FROM channel_info WHERE id=%d", array($_channelID));
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    $_channelName = $list[0]["channel_name"];
                } else {
                    $this->_status = 1;
                }
                db_free($rs);
            } else {
                $this->_status = 1;
            }
            if ($this->_status == 0) {
                $cl = new ListModel(__CLASS__, "classlist_item");

                $rs = db_query("SELECT * FROM class_info WHERE parent_channel=%d", array($_channelID));
                if ($rs) {
                    $list = db_result($rs);
                    foreach ($list as $item) {
                        $cl->AddItem(array(
                            "ID" => $item["id"],
                            "Name" => Len_Control($item["class_name"], 30)
                        ));
                    }
                    db_free($rs);
                }

                $clipboard = new ClipBoard();
                if ($clipboard->isCut("content")) {

                    $c = $clipboard->getValue("content");

                    $cl->SetContainer("classlist", array(
                        "Buttons" => TransformTpl("classlist_btn_move", array(
                            "channelid" => $_channelID,
                            "type" => $c[1],
                            "contentid" => $c[0]
                                ), __CLASS__)
                    ));
                } else {

                    $cl->SetContainer("classlist", array(
                        "Buttons" => TransformTpl("classlist_btn", array(
                            "channelid" => $_channelID
                                ), __CLASS__)
                    ));
                }

                $this->SetHeight("auto");
                $this->SetTitle(GetLangData("classadmin") . " ( " . GetLangData("channel") . ": {$_channelName} )");
                $this->SetContent($cl->GetHTML(), "center", "middle", 30);
            }
        } else {
            $this->_status = 1;
        }
        if ($this->_status == 1) {
            $this->_error = "not found";
            //TODO error message
        }
    }

}