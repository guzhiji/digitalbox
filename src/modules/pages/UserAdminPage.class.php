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

class UserAdminPage extends AdminPage {

    protected function Initialize() {
        parent::Initialize();
        $this->AddBox(new Admin_NaviBox("Right", GetLangData("useradmin"), array(
                    GetLangData("changepwd") => array("admin_account.php?module=changepwd", FALSE),
                    GetLangData("adduser") => array("admin_account.php?module=add", TRUE),
                    GetLangData("deluser") => array("admin_account.php?module=delete", TRUE)
                )));
    }

    public function SetTitle($title="") {
        if ($title === NULL || $title == "")
            parent::SetTitle(GetLangData("useradmin"));
        else
            parent::SetTitle($title . " - " . GetLangData("useradmin"));
    }

}
