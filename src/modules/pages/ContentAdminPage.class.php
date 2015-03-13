<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require_once("modules/pages/AdminPage.class.php");

class ContentAdminPage extends AdminPage {

    protected function Initialize() {
        parent::Initialize();
        $this->AddBox(new Admin_NaviBox("Right", GetLangData("contentadmin"), array(
                    GetLangData("contentdirectory") => array("admin_channel.php", FALSE),
                    GetLangData("contentupload") => array("admin_upload.php", FALSE),
                    GetLangData("contentrecycle") => array("admin_recyclebin.php", FALSE)
                )));
    }

    public function SetTitle($title="") {
        if ($title === NULL || $title == "")
            parent::SetTitle(GetLangData("contentadmin"));
        else
            parent::SetTitle($title . " - " . GetLangData("contentadmin"));
    }

}
