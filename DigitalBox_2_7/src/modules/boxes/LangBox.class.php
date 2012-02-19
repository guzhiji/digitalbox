<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class LangBox extends Box {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {
        $lang = GetLang();
        $languages = GetSettingValue("languages");
        $list = new ListModel(__CLASS__, "item");
        $list->SetContainer("container", array(
            "Referrer" => array_key_exists("HTTP_REFERER", $_SERVER) ? $_SERVER["HTTP_REFERER"] : ""
        ));
        foreach ($languages as $code => $name) {
            $list->AddItem(array(
                "Code" => $code,
                "Checked" => $code == $lang ? " checked=\"checked\"" : "",
                "Name" => $name
            ));
        }

        $this->SetHeight("auto");
        $this->SetTitle(GetLangData("changelang"));
        $this->SetContent($list->GetHTML(), "center", "middle", 2);
    }

}
