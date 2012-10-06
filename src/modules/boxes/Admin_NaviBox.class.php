<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_NaviBox extends BoxModel {

    protected $_itemtpl;
    protected $_eventdriven;
    protected $_title;
    protected $_menu;

    function __construct($type, $title, array $menu) {
        parent::__construct($type, "");
        if ($type == "TopNaviBar") {
            $this->_itemtpl = "navilink";
            $this->_eventdriven = FALSE;
        } else {
            $this->_type = "Right";
            $this->_tplName = "box1";
            $this->_itemtpl = "navibutton";
            $this->_eventdriven = TRUE;
        }
        $this->_title = $title;
        $this->_menu = $menu;
    }

    public function CacheBind() {
        
    }

    public function DataBind() {
        global $_passport;
        $ismaster = FALSE;
        if (isset($_passport) && $_passport->isMaster()) {
            $ismaster = TRUE;
        }

        $eventdriven = $this->_eventdriven;
        $nb = new Navigator($this->_itemtpl);

        foreach ($this->_menu as $name => $value) {
            if (!$value[1] || $ismaster) {
                $nb->AddItem($name, $value[0], $name, FALSE, $eventdriven);
            }
        }

        $this->SetHeight("auto");
        $this->SetTitle($this->_title);
        $this->SetContent($nb->GetHTML(), "center", "middle", 0);
    }

}
