<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require_once("modules/uimodel/PageModel.class.php");

class PopupPage extends PageModel {

    function __construct() {
        parent::__construct("popuppage");

        $this->AddMeta("robots", "noindex,nofollow");
        $this->AddCSSFile(GetThemeResPath("main.css", "stylesheets"));
        //icon
        $icon = GetSettingValue("icon_URL");
        if (!empty($icon)) {
            $this->SetIcon($icon);
        }
    }

    protected function After($page) {

        $this->SetField('SiteName', GetSettingValue('site_name'));
        
    }

}
