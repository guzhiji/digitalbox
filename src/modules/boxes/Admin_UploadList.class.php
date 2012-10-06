<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_UploadList extends BoxModel {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {

        require("modules/uploadlist.module.php");
        $html = GetTemplate("uploadlist_js");
        $uplist = GetUploadList(GetSettingValue("general_list_maxlen"), array(
            "value" => "上传",
            "action" => "window.location.href='admin_upload.php?function=uploadform'"
                ), array(
            "value" => "删除",
            "action" => "delete_upload()"
                ));
        $html.= $uplist->GetHTML();
        $this->SetHeight("auto");
        $this->SetTitle("上传管理");
        $this->SetContent($html, "center", "middle", 30);
    }

}
