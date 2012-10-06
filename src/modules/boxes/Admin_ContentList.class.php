<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_ContentList extends BoxModel {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {

        global $_classID;
        global $_channelType;
        $_channelType = 0;
        if (isset($_classID)) {
            $rs = db_query("SELECT channel_info.id AS channel_id,channel_info.channel_type,channel_info.channel_name,class_info.class_name FROM class_info,channel_info WHERE channel_info.id=class_info.parent_channel AND class_info.id=%d", array($_classID));
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    global $_channelID;
                    global $_channelName;
                    global $_className;

                    $_channelType = $list[0]["channel_type"];
                    $_channelID = $list[0]["channel_id"];
                    $_channelName = $list[0]["channel_name"];
                    $_className = $list[0]["class_name"];
                }
                db_free($rs);
            }
        }
        if ($_channelType == 0) {
            unset($_channelType);
            $this->_status = 1;
            $this->_error = "找不到此栏目";
            $this->_title = GetLangData("error");
            $this->_backpage = "back";
        } else {
            $type_en = GetTypeName($_channelType, 1);
            $type_cn = GetTypeName($_channelType, 0);

            require("modules/ContentList.class.php");
            $contentlist = new ContentList("id");
            $contentlist->SetClass($_classID, $_className, $_channelType);
            $contentlist->SetTitleList(GetSettingValue("general_list_maxlen"), GetSettingValue("box3_title_maxlen"), FALSE, FALSE, FALSE, 2);
            $contentlist->SetImageList(GetSettingValue("general_grid_maxrow"), 5, 2);

            $html = $contentlist->GetHTML(1, 2, "admin_content.php?module=content&class={$_classID}");

            $clipboard = new ClipBoard();
            if ($clipboard->isCut("content")) {

                $c = $clipboard->getValue("content");
                $html = TransformTpl("contentlist_admin", array(
                    "ListItems" => $html,
                    "Buttons" => TransformTpl("contentlist_admin_btn_move", array(
                        "classid" => $_classID,
                        "type" => $c[1],
                        "contentid" => $c[0],
                        "channelid" => $_channelID
                            ), __CLASS__)
                        ), __CLASS__);
            } else {

                $html = TransformTpl("contentlist_admin", array(
                    "ListItems" => $html,
                    "Buttons" => TransformTpl("contentlist_admin_btn", array(
                        "classid" => $_classID,
                        "type_en" => $type_en,
                        "type_cn" => $type_cn,
                        "channelid" => $_channelID
                            ), __CLASS__)
                        ), __CLASS__);
            }

            $this->SetHeight("auto");
            $this->SetTitle(GetLangData("contentadmin") . " ( " . GetLangData("class") . ": {$_className} )");
            $this->SetContent($html, "center", "middle", 30);
        }
    }

}