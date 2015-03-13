<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_UserList extends Box {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {

        $totalcount = 0;
        $pagesize = GetSettingValue("general_list_maxlen");

        require_once("modules/uimodel/ListModel.class.php");
        $userlist = new ListModel(__CLASS__, "userlist_item");

        $rs = db_query("SELECT count(*) FROM admin_info");
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
        $userlist->SetContainer("userlist", array(
            "Master" => GetSettingValue("master_name"),
            "PagingBar" => $pb->GetHTML()
        ));
        //$pagecount = $pb->GetPageCount();
        $pagenumber = $pb->GetPageNumber();
        $start = $pagesize * ($pagenumber - 1);
        $rs = db_query("SELECT admin_UID FROM admin_info LIMIT $start,$pagesize");
        if ($rs) {
            $list = db_result($rs);
            if ($totalcount > 0) {
                foreach ($list as $item) {
                    $userlist->AddItem(array(
                        "UID" => $item["admin_UID"]
                    ));
                }
            }
            db_free($rs);
        }

        $this->SetHeight("auto");
        $this->SetTitle("管理人员列表");
        $this->SetContent($userlist->GetHTML(), "center", "middle", 2);
    }

}
