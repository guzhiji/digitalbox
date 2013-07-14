<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require("modules/Passport.class.php");
require("modules/Stopwatch.class.php");
require("modules/uimodel/PageModel.class.php");
require("modules/lists/Navigator.class.php");
require("modules/boxes/BannerBox.class.php");
require("modules/boxes/Admin_NaviBox.class.php");
require("modules/boxes/Admin_BNaviBox.class.php");

class AdminPage extends PageModel {

    protected $_mainmenu;
    private $_timer;

    function __construct() {
        parent::__construct("adminpage");
    }

    protected function Initialize() {
        $this->_timer = new Stopwatch();
        //passport
        global $_passport;
        if (!isset($_passport))
            $_passport = new Passport();
        if (!$_passport->check()) {
            PageRedirect("login.php");
        }

        $this->AddCSSFile(GetThemeResPath("main.css", "stylesheets"));
        $this->AddMeta("robots", "noindex,nofollow");
        //icon
        $icon = GetSettingValue("icon_URL");
        if (!empty($icon)) {
            $this->SetIcon($icon);
        }

        $this->_mainmenu = array(
            GetLangData("homepage") => array("admin.php", FALSE),
            GetLangData("useradmin") => array("admin_account.php", FALSE),
            GetLangData("contentadmin") => array("admin_channel.php", FALSE),
            GetLangData("eventadmin") => array("admin_event.php", FALSE),
            GetLangData("settingadmin") => array("admin_setting.php", TRUE),
            GetLangData("logout") => array("login.php?function=logout", FALSE)
        );
    }

    protected function Finalize() {
        $this->AddBox(new BannerBox());
        $this->AddBox(new Admin_NaviBox("Right", GetLangData("navigation"), $this->_mainmenu));
        $this->AddBox(new Admin_NaviBox("TopNaviBar", "", $this->_mainmenu));
        $this->AddBox(new Admin_BNaviBox());
        $this->_regions["Footer"] = GetSettingValue("site_statement");
        $this->_regions["MasterMail"] = GetSettingValue("master_mail");
        $this->_regions["MasterName"] = GetSettingValue("master_name");
        $this->_regions["VisitorCount"] = GetVisitorCount();
        $this->_regions["SiteName"] = GetSettingValue("site_name");
        if (isset($this->_timer))
            $this->_regions["ElapsedTime"] = $this->_timer->elapsedMillis();
        else
            $this->_regions["ElapsedTime"] = 0;
    }

    public function AddBoxFactory(BoxFactory $factory) {
        $region = $factory->GetType();
        switch ($region) {
            case "Left":
            case "Right":
                parent::AddBoxFactory($region, $factory);
                break;
        }
    }

    public function AddBox(BoxModel $box) {
        $region = $box->GetType();
        switch ($region) {
            case "Left":
            case "Right":
            case "Banner":
            case "TopNaviBar":
            case "BottomNaviBar":
                //switch ($box->GetStatus()) {
                //    case 0:
                parent::AddBox($region, $box);
                //        break;
                //    case 1:
                //        $e = $box->GetError();
                //        $t = $box->GetTitle();
                //        $b = $box->GetBackPage();
                //        parent::AddBox($region, new MsgBox(ErrorList($e), $t, $b));
                //        break;
                //}
                break;
        }
    }

}
