<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require_once("modules/pages/AdminPage.class.php");

class EventAdminPage extends AdminPage {

    protected function Initialize() {
        parent::Initialize();
        $this->AddBox(new Admin_NaviBox("Right", GetLangData("eventadmin"), array(
                    GetLangData("notice") => array("admin_event.php?module=notice", FALSE),
                    GetLangData("vote") => array("admin_event.php?module=vote", FALSE),
                    GetLangData("comments") => array("admin_event.php?module=comment", FALSE)
                )));
    }

    public function SetTitle($title="") {
        if ($title === NULL || $title == "")
            parent::SetTitle(GetLangData("eventadmin"));
        else
            parent::SetTitle($title . " - " . GetLangData("eventadmin"));
    }

}
