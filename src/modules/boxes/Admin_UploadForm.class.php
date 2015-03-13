<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_UploadForm extends Box {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {

        $html = TransformTpl("upload", array(
            "Upload_FileTypes" => strtoupper(str_replace(";", " ", GetSettingValue("upload_filetypes"))),
            "Upload_MaxSize" => GetSizeWithUnit(GetSettingValue("upload_maxsize"))
                ), __CLASS__);

        $this->SetHeight("auto");
        $this->SetTitle("上传管理");
        $this->SetContent($html, "center", "middle", 30);
    }

}
