<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require_once("modules/uimodel/ListModel.class.php");

class SiteList extends ListModel {

    private $containertplname = "";
    private $formore = 0;

    function __construct($itemtplname, $emptyTplName = "") {
        parent::__construct(__CLASS__, $itemtplname);
        $this->SetEmptyItem($emptyTplName);
    }

    public function SetContainer($tplname, $formore = 0) {
        $this->formore = $formore;
        $this->containertplname = $tplname;
    }

    public function AddItem($name, $addr, $text, $logo, $id = 0) {

        //Len_Control($item["site_name"], $title_maxlen);
        parent::AddItem(array(
            "Name" => $name,
            "Add" => htmlspecialchars($addr),
            "Text" => $text,
            "Logo" => htmlspecialchars($logo),
            "ID" => $id
        ));
    }

    public function Bind($page_size = 0) {

        //initialize variables
        $total_record = 0;
        $page_number = 1;
        $total_page = 1;
        if ($page_size < 1)
            $page_size = GetSettingValue("general_list_maxlen");

        //count
        if ($this->formore == 2) {
            $rs = db_query("SELECT COUNT(*) FROM friendsite_info");
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    $total_record = $list[0][0];
                }
                db_free($rs);
            }
        }

        //pagination
        if ($this->containertplname != "") {
            switch ($this->formore) {
                case 1:
                    parent::SetContainer($this->containertplname, array(
                        "MoreButton" => GetThemeResPath("button/more.gif", "images", GetThemeID())
                    ));
                    break;
                case 2:
                    require_once("modules/PagingBar.class.php");
                    $pb = new PagingBar();
                    $pb->SetPageCount($total_record, $page_size);
                    $total_page = $pb->GetPageCount();
                    $page_number = $pb->GetPageNumber();
                    parent::SetContainer($this->containertplname, array(
                        "PagingBar" => $pb->GetHTML()
                    ));
                    break;
                default:
                    parent::SetContainer($this->containertplname, array());
            }
        }

        //fetch data
        $rs = db_query("SELECT * FROM friendsite_info LIMIT " . ($page_number - 1) * $page_size . "," . $page_size);
        if ($rs) {
            $list = db_result($rs);
            foreach ($list as $item) {
                $this->AddItem(
                        $item["site_name"], $item["site_add"], $item["site_text"], $item["site_logo"], $item["id"]
                );
            }
            db_free($rs);
        }

//	if($html=="") $html="暂无友情链接";
//	$box->SetContent ($html,"center","top",5);
    }

}
