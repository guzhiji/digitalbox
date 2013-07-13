<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class PortalPage extends PageModel {

    private $_timer;

    /**
     * constructor
     */
    function __construct() {
        parent::__construct('portalpage', NULL);
        $this->contentFieldName = 'Left';
    }

    public function After($page) {
        //$this->AddBox(new BannerBox());
        //$this->AddBox(new ChannelNaviBox("TopNaviBar"));
        //$this->AddBox(new BottomNaviBox());
        $this->SetField("Version", '');
        $this->SetField("BottomNaviBar", '');
        $this->SetField("Right", '');
        $this->SetField("TopNaviBar", '');
        $this->SetField("Banner", '');
        $this->SetField("Footer", ''); //GetSettingValue("site_statement");
        $this->SetField("MasterMail", ''); // GetSettingValue("master_mail");
        $this->SetField("MasterName", ''); //GetSettingValue("master_name");
        $this->SetField("VisitorCount", ''); //GetVisitorCount();
        $this->SetField("SiteName", ''); //GetSettingValue("site_name");
        if (isset($this->_timer))
            $this->SetField("ElapsedTime", $this->_timer->elapsedMillis());
        else
            $this->SetField("ElapsedTime", 0);
    }

    public function Before($page) {
        LoadIBC1Class('Stopwatch', 'util');
        $this->_timer = new Stopwatch();
        $this->AddCSSFile('main.css', 1);
        //$this->SetDescription(GetSettingValue("site_keywords"));
        //$this->AddKeywords(GetSettingValue("site_keywords"));
        //icon
//        $icon = GetSettingValue("icon_URL");
//        if (!empty($icon)) {
//            $this->SetIcon($icon);
//        }
//        //addon scripts
//        $scripts = explode(",", GetSettingValue("portal_scripts"));
//        foreach ($scripts as $s) {
//            $this->AddJSFile(GetSysResPath("{$s}.js", "scripts/addons"));
//        }
    }

}
