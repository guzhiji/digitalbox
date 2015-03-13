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

class SettingAdminPage extends AdminPage {

    protected function Initialize() {
        parent::Initialize();
        $this->AddBox(new Admin_NaviBox("Right", GetLangData("settingadmin"), array(
                    GetLangData("basicsettings") => array("admin_setting.php?module=basic", TRUE),
                    GetLangData("detailsettings") => array("admin_setting.php?module=detail", TRUE),
                    GetLangData("themesettings") => array("admin_theme.php", TRUE),
                    GetLangData("friendsites") => array("admin_friendsite.php", TRUE),
                    GetLangData("adsettings") => array("admin_ads.php", TRUE),
                    GetLangData("scriptsettings") => array("admin_script.php", TRUE)
                )));
    }

    public function SetTitle($title="") {
        if ($title === NULL || $title == "")
            parent::SetTitle(GetLangData("settingadmin"));
        else
            parent::SetTitle($title . " - " . GetLangData("settingadmin"));
    }

}
