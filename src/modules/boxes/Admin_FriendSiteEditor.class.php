<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_FriendSiteEditor extends Box {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {

        $data = NULL;
        switch (strGet("function")) {
            case "save":
                $rs = db_query("SELECT * FROM friendsite_info WHERE id=%d", array(strGet("id")));
                if ($rs) {
                    $list = db_result($rs);
                    if (isset($list[0])) {
                        require("modules/filters.lib.php");
                        $data = array(
                            "Setting_FSite_Function" => "save",
                            "Setting_FSite_Name" => TextForInputBox($list[0]["site_name"]),
                            "Setting_FSite_Add" => TextForInputBox($list[0]["site_add"]),
                            "Setting_FSite_Logo" => TextForInputBox($list[0]["site_logo"]),
                            "Setting_FSite_Text" => TextForInputBox($list[0]["site_text"]),
                            "Setting_FSite_ID" => $list[0]["id"]
                        );
                    }
                }

                break;
            default:
                $data = array(
                    "Setting_FSite_Function" => "add",
                    "Setting_FSite_Name" => "",
                    "Setting_FSite_Add" => "",
                    "Setting_FSite_Logo" => "",
                    "Setting_FSite_Text" => "",
                    "Setting_FSite_ID" => ""
                );
                break;
        }
        if ($data != NULL) {
            $html = TransformTpl("setting_friendsite", $data, __CLASS__);
            $this->SetHeight("auto");
            $this->SetTitle(GetLangData("friendsites"));
            $this->SetContent($html, "center", "middle", 2);
        } else {
            $this->_status = 1;
            $this->SetTitle(GetLangData("error"));
            $this->_error = "找不到此链接！";
            $this->_backpage = "back";
        }
    }

}
