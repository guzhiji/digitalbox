<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_StyleList extends BoxModel {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {
        $totalcount = 0;
        $pagesize = 10;
        $defaultstyle = "";
        $defaultstyleid = GetSettingValue("default_style");
        $defaultstyletext = "未设置默认风格";
        $rs = db_query("SELECT count(*) FROM style_info");
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0])) {
                $totalcount = $list[0][0];
            }
            db_free($rs);
        }
        require_once("modules/uimodel/ListModel.class.php");
        require_once("modules/PagingBar.class.php");
        $stylelist = new ListModel(__CLASS__, "stylelist_edit_item");
        $pb = new PagingBar();
        $pb->SetPageCount($totalcount, $pagesize);
        //$pagecount = $pb->GetPageCount();
        $pagenumber = $pb->GetPageNumber();
        if ($totalcount > 0) {
            $start = $pagesize * ($pagenumber - 1);
            $rs = db_query("SELECT * FROM style_info LIMIT $start,$pagesize");
            if ($rs) {
                $list = db_result($rs);
                foreach ($list as $item) {
                    $stylelist->AddItem(array(
                        "ID" => $item["id"],
                        "Name" => $item["style_name"]
                    ));
                    if ($item["id"] == $defaultstyleid)
                        $defaultstyle = $item["style_name"];
                }
            }
            db_free($rs);

            if ($defaultstyle != "") {
                $defaultstyletext = "现使用的默认风格为：$defaultstyle";
            }
            $stylelist->SetContainer("stylelist_edit", array(
                "Default" => $defaultstyletext,
                "PagingBar" => $pb->GetHTML()
            ));
        } else {
            $stylelist->SetContainer("stylelist_edit", array(
                "Default" => $defaultstyletext,
                "PagingBar" => $pb->GetHTML()
            ));
        }

        $this->SetHeight("auto");
        $this->SetTitle("风格设置");
        $this->SetContent($stylelist->GetHTML(), "center", "middle", 20);
    }

}
