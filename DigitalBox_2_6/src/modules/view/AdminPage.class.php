<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/AdminPage.class.php
  ------------------------------------------------------------------
 */
require_once("modules/view/Page.class.php");
require_once("modules/view/Navigator.class.php");
require_once("modules/Passport.class.php");

class AdminPage extends Page {

    protected $_passport, $_mainmenu;

    function __construct() {
        parent::__construct("adminpage");

        $passport = new Passport();
        if (!$passport->check()) {
            //TODO:debug
            PageRedirect("login.php");
        }
        $this->_passport = &$passport;

        //variables
//        $this->_passport = &$passport;
//        $this->_connid = &$connid;
//        $this->_passport->_connid = &$connid;

        $this->_prefix = __CLASS__;

        $this->_mainmenu = array(
            "首 页" => array("admin.php", FALSE),
            "账 户" => array("admin_account.php", FALSE),
            "内 容" => array("admin_content.php", FALSE),
            "活 动" => array("admin_event.php", FALSE),
            "设 置" => array("admin_setting.php", TRUE),
            "退 出" => array("login.php?function=logout", FALSE));

        $this->_passport->setConn($this->GetDBConn());
        $this->SetCSSFile("main.css");
        $this->SetNavigator($this->NavBar1(), $this->NavBar2());
        $this->AddMeta("robots", "noindex,nofollow");
    }

    public function GetPassport() {
        return $this->_passport;
    }

    //------------------------------------------------------------------
    protected function NavBar1() {
        $nb = new Navigator("navilink1");
        foreach ($this->_mainmenu as $name => $value) {
            if (!$value[1] || $this->_passport->isMaster()) {
                $nb->AddItem($name, $value[0]);
            }
        }

        return $nb;
    }

    //------------------------------------------------------------------
    protected function NavBar2() {
        $nb = new Navigator("navilink1");

        if (GetSettingValue("search_visible"))
            $nb->AddItem("搜&nbsp;&nbsp;&nbsp;&nbsp;索", "search.php", "", FALSE, FALSE, "_blank");

        if (GetSettingValue("guestbook_visible"))
            $nb->AddItem("留&nbsp;&nbsp;&nbsp;&nbsp;言", "guestbook.php", "", FALSE, FALSE, "_blank");

        $nb->AddItem("联系站长", "mailto:" . GetSettingValue("master_mail"));

        if (GetSettingValue("style_changeable"))
            $nb->AddItem("选择风格", "style.php");

        if (GetSettingValue("friendsite_visible"))
            $nb->AddItem("友情链接", "friendsite.php", "", FALSE, FALSE, "_blank");

        $nb->AddItem("退出系统", "login.php?function=logout");

        return $nb;
    }

    //------------------------------------------------------------------
    public function ShowNavBox1() {

        $nb = new Navigator("navibutton1");

        foreach ($this->_mainmenu as $name => $value) {

            if (!$value[1] || $this->_passport->isMaster()) {
                $nb->AddItem($name, $value[0], $name, FALSE, TRUE);
            }
        }

        $box = new Box(1);
        $box->SetHeight("auto");
        $box->SetTitle("导航");
        $box->SetContent($nb->GetHTML(), "center", "middle", 0);
        $this->AddToRight($box);
    }

    public function ShowNavBox2($title, &$menu) {
        $nb = new Navigator("navibutton1");
        foreach ($menu as $name => $value) {
            if (!$value[1] || $this->_passport->isMaster()) {
                $nb->AddItem($name, $value[0], $name, FALSE, TRUE);
            }
        }

        $box = new Box(1);
        $box->SetHeight("auto");
        $box->SetTitle($title);
        $box->SetContent($nb->GetHTML(), "center", "middle", 0);
        $this->AddToRight($box);
    }

    //------------------------------------------------------------------


    public function RaiseError() {
        $this->_mode = "error";
    }

}

?>