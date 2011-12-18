<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/SettingAdminPage.class.php
  ------------------------------------------------------------------
 */
require_once("modules/view/AdminPage.class.php");

class SettingAdminPage extends AdminPage {

    protected $_submenu;

    function __construct() {
        parent::__construct();
        $this->_submenu = array(
            "基础设置" => array("admin_setting.php?module=basic", TRUE),
            "具体设置" => array("admin_setting.php?module=detail", TRUE),
            "风格设置" => array("admin_style.php", TRUE),
            "友情链接" => array("admin_friendsite.php", TRUE),
            "广告设置" => array("admin_ads.php", TRUE),
            "脚本设置" => array("admin_script.php", TRUE)
        );
    }

    public function ShowNavBox2() {
        parent::ShowNavBox2("设置管理", $this->_submenu);
    }

    public function SetTitle($title="") {
        if ($title === NULL || $title == "")
            parent::SetTitle("设置管理");
        else
            parent::SetTitle($title . " - 设置管理");
    }

}

?>
