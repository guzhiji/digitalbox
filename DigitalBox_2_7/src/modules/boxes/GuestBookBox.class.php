<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class GuestBookBox extends Box {

    protected $key;

    function __construct($key = "") {
        parent::__construct($key != "" ? "Left" : "Right", $key != "" ? "box3" : "box1");
        $this->key = $key;
        if (!GetSettingValue("guestbook_visible"))
            $this->_status = 2;
    }

    public function CacheBind() {
        if ($this->key == "") {
            $this->_cacheCategory = "portalpage";
            $this->_cacheKey = "guestbook";
            $this->_cacheExpire = dbCacheTimeout;
            $this->_cacheVersion = 0;
        }
    }

    public function DataBind() {

        require_once("modules/lists/CommentList.class.php");
        $cl = new CommentList("commentlist_item");
        $cl->SetContainer("commentlist_container", 1);
        $cl->Bind($this->key, GetSettingValue("comment_list_maxlen"));
        if ($cl->type == 3) {
            $this->SetTitle(GetLangData("comments"));
            $this->SetContent($cl->GetHTML(), "left", "middle", 10);
        } else {
            $this->SetTitle(GetLangData("guestbook"));
            $this->SetContent($cl->GetHTML(), "left", "middle", 10);
        }

        //Len_Control($item["guest_name"], $sgb_maxlen);
    }

}
