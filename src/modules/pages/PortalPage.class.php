<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require_once("modules/uimodel/PageModel.class.php");
require_once("modules/Stopwatch.class.php");
require("modules/lists/Navigator.class.php");
require("modules/boxes/BannerBox.class.php");
require("modules/boxes/ChannelNaviBox.class.php");
require("modules/boxes/BottomNaviBox.class.php");

class PortalPage extends PageModel {

    private $_timer;

    /**
     * constructor
     */
    function __construct() {
        parent::__construct("portalpage");
    }

    protected function Initialize() {
        $this->_timer = new Stopwatch();
        $this->AddCSSFile(GetThemeResPath("main.css", "stylesheets"));
        $this->SetDescription(GetSettingValue("site_keywords"));
        $this->AddKeywords(GetSettingValue("site_keywords"));
        //icon
        $icon = GetSettingValue("icon_URL");
        if (!empty($icon)) {
            $this->SetIcon($icon);
        }
        //addon scripts
        $scripts = explode(",", GetSettingValue("portal_scripts"));
        foreach ($scripts as $s) {
            $this->AddJSFile(GetSysResPath("{$s}.js", "scripts/addons"));
        }
    }

    protected function Finalize() {
        $this->AddBox(new BannerBox());
        $this->AddBox(new ChannelNaviBox("TopNaviBar"));
        $this->AddBox(new BottomNaviBox());
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

    public function AddBox(Box $box) {
        $region = $box->GetType();
        switch ($region) {
            case "Left":
            case "Right":
            case "Banner":
            case "TopNaviBar":
            case "BottomNaviBar":
                parent::AddBox($region, $box);
                break;
        }
    }

}
