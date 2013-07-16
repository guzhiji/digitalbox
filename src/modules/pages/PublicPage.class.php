<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class PublicPage extends PageModel {

    private $_timer;

    /**
     * constructor
     */
    function __construct() {
        parent::__construct('publicpage', NULL);
        $this->contentFieldName = 'Left';
    }

    public function GetVersion() {
        return 'DigitalBox CMS ' . dbVersion;
    }

    public function After($page) {
        //$this->AddBox(new BannerBox());
        //$this->AddBox(new ChannelNaviBox("TopNaviBar"));
        //$this->AddBox(new BottomNaviBox());
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
    }

}
