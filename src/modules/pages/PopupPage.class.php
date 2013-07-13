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

    public function AddBox(BoxModel $box) {
        $region = $box->GetType();
        switch ($region) {
            case "Left":
                parent::AddBox($region, $box);
                break;
        }
    }

    protected function Finalize() {

        $this->_regions["SiteName"] = GetSettingValue("site_name");
    }

}
