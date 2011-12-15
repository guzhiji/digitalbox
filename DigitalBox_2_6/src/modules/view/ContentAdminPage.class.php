<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/ContentAdminPage.class.php
  ------------------------------------------------------------------
 */
require_once("modules/view/AdminPage.class.php");

class ContentAdminPage extends AdminPage {

    protected $_submenu;

    function __construct() {
        parent::__construct();
        $this->_submenu = array(
            "浏览目录" => array("admin_content.php", FALSE),
            "上传管理" => array("admin_upload.php", FALSE),
            "回收管理" => array("admin_recyclebin.php", FALSE)
        );
    }

    public function ShowNavBox2() {
        parent::ShowNavBox2("内容管理", $this->_submenu);
    }

    public function SetTitle($title="") {
        if ($title === NULL || $title == "")
            parent::SetTitle("内容管理");
        else
            parent::SetTitle($title . " - 内容管理");
    }

}

?>
