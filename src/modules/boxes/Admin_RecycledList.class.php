<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_RecycledList extends Box {

    private $type;

    function __construct($type) {
        parent::__construct("Left", "box3");
        $this->type = $type;
    }

    public function DataBind() {
        $html = "";
        for ($i = 1; $i < 5; $i++) {
            if ($i != $this->type) {
                $html.="<option value=\"{$i}\">" . GetTypeName($i, 0) . "</option>";
            }
        }

        $type_cn = GetTypeName($this->type, 0);
        $type_en = GetTypeName($this->type, 1);

        $totalcount = 0;
        $pagesize = GetSettingValue("general_list_maxlen");
        $rs = db_query("SELECT count(*) FROM " . GetTypeName($this->type, 1) . "_info WHERE parent_class<1");
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0])) {
                $totalcount = $list[0][0];
            }
            db_free($rs);
        }

        require_once("modules/PagingBar.class.php");
        $pb = new PagingBar();
        $pb->SetPageCount($totalcount, $pagesize);
        //$pagecount = $pb->GetPageCount();
        $pagenumber = $pb->GetPageNumber();

        require_once("modules/uimodel/ListModel.class.php");
        $rl = new ListModel(__CLASS__, "recyclebin_item");
        $rl->SetContainer("recyclebin_list", array(
            "Types" => $html,
            "PagingBar" => $pb->GetHTML(),
            "Type_cn" => $type_cn,
            "Type_en" => $type_en,
            "Type" => $this->type
        ));

        if ($totalcount > 0) {

            $start = $pagesize * ($pagenumber - 1);
            $rs = db_query("SELECT * FROM {$type_en}_info WHERE parent_class<1 LIMIT {$start},{$pagesize}");
            if ($rs) {
                $list = db_result($rs);
                foreach ($list as $item) {

                    $rl->AddItem(array(
                        "ID" => $item["id"],
                        "Name" => $item["{$type_en}_name"]
                    ));
                }
                db_free($rs);
            }
        }

        $this->SetHeight("auto");
        $this->SetTitle("回收站管理 ({$type_cn})");
        $this->SetContent($rl->GetHTML(), "center", "middle", 30);
    }

}
