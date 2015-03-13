<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class StyleListBox extends Box {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {

        $stylelist = new ListModel(__CLASS__, "stylelist_select_item");
        $stylelist->SetContainer("stylelist_select", array(
            "Referrer" => array_key_exists("HTTP_REFERER", $_SERVER) ? $_SERVER["HTTP_REFERER"] : ""
        ));
        $style = GetThemeID();

        $rs = db_query("SELECT * FROM style_info");
        if ($rs) {
            $list = db_result($rs);
            foreach ($list as $item) {
                $stylelist->AddItem(array(
                    "ID" => $item["id"],
                    "Checked" => $style == intval($item["id"]) ? " checked=\"checked\"" : "",
                    "Name" => $item["style_name"]
                ));
            }
            db_free($rs);
        }

        $this->SetTitle(GetLangData("changetheme"));
        $this->SetContent($stylelist->GetHTML(), "center", "middle", 5);
    }

}
