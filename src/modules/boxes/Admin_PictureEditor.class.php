<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_PictureEditor extends BoxModel {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {

        $this->SetAlign("center", "top");
        $this->SetPadding(10);

        global $_contentID;
        global $_classID;
        global $_className;
        global $_channelType;
        $title = "";

        $typename = GetTypeName($_channelType, 0);
        if (isset($_contentID) && !empty($_contentID)) {
            //MODIFY CONTENT
            $title = GetLangData("edit") . $typename;
            //prepare sql
            require_once("modules/data/sql_content.module.php");
            $sql = new SQL_Content();
            $sql->SetMode($_channelType);
            $sql->SetContentID($_contentID);
            $sql->AddField("picture_add");
            $sql->AddField("picture_text");

            //run sql
            $rs = db_query($sql->GetSelect());
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    //generate html
                    require("modules/filters.lib.php");
                    $this->_content = TransformTpl("picture_editor", array(
                        "Content_Function" => "save",
                        "Content_Class" => $list[0]["class_id"],
                        "Content_ClassName" => $list[0]["class_name"],
                        "Content_ID" => intval($_contentID),
                        "Content_Name" => TextForInputBox($list[0]["content_name"]),
                        "Content_Addr" => TextForInputBox($list[0]["picture_add"]),
                        "Content_Text" => HTMLForTextArea($list[0]["picture_text"])
                            ), __CLASS__);
                } else {
                    $this->_status = 1;
                }
                db_free($rs);
            } else {
                $this->_status = 1;
            }
        } else if (isset($_classID) && isset($_className)) {
            //CREATE NEW CONTENT
            $title = GetLangData("add") . $typename;
            //generate html
            $this->_content = TransformTpl("picture_editor", array(
                "Content_Function" => "add",
                "Content_Class" => $_classID,
                "Content_ClassName" => $_className,
                "Content_ID" => "",
                "Content_Name" => "",
                "Content_Addr" => dbUploadPath . "/",
                "Content_Text" => ""
                    ), __CLASS__);
        } else {
            $this->_status = 1;
        }

        if ($this->_status == 1) {
            $this->SetTitle(GetLangData("error"));
            $this->_error = "not found"; //TODO error message
            $this->_backpage = "back";
        } else {
            $this->SetTitle($title);
        }
    }

}
