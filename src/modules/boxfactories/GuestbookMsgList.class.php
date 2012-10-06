<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class GuestbookMsgList extends BoxFactory {

    protected $_pagenumber;

    function __construct($pagenumber) {
        parent::__construct("Left");
        $this->_pagenumber = $pagenumber;
        require("modules/boxes/GuestbookMessage.class.php");
    }

//    public function CacheBind() {
//        $page = intval(strGet("page"));
//        if ($page == 0)
//            $page = 1;
//        $this->_cacheCategory = "guestbook";
//        $this->_cacheKey = "msglist_" . $page;
//        $this->_cacheTimeout = -1;
//        $this->_cacheVersion = GetSettingValue("version_guestbook");
//    }

    public function DataBind() {

        $pagesize = GetSettingValue("guestbook_list_maxlen");

        $rs = NULL;
        $start = ($this->_pagenumber - 1) * $pagesize;
        $guest_id = strGet("id");
        if ($guest_id != "") {
            $rs = db_query("SELECT * FROM guest_info WHERE id=%d", array($guest_id));
        } else {
            $rs = db_query("SELECT * FROM guest_info ORDER BY guest_date DESC LIMIT {$start},{$pagesize}");
        }
        $list = NULL;
        if ($rs) {
            $list = db_result($rs);
            if (!isset($list[0])) {
                $list = NULL;
            }
            db_free($rs);
        }
        if ($list == NULL) {
            //error information
            $box = new Box("Left", "box3");
            $box->SetHeight("auto");
            if ($guest_id != "") {
                $box->SetTitle(GetLangData("error"));
                $box->SetContent(GetLangData("notfound"), "center", "middle", 2);
            } else {
                $box->SetTitle(GetLangData("guestbook"));
                $box->SetContent(GetLangData("guestbook_empty"), "center", "middle", 2);
            }
            $this->AddBox($box);
        } else {
            //comment list
            foreach ($list as $item) {
                $this->AddBox(new GuestbookMessage($item));
            }
        }
    }

}
