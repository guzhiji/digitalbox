<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class GuestbookCtlBox extends Box {

    protected $_pagenumber;

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function GetPageNumber() {
        return $this->_pagenumber;
    }

    public function DataBind() {
        $pagesize = GetSettingValue("guestbook_list_maxlen");

        $record_count = 0;
        $rs = db_query("SELECT count(*) FROM guest_info");
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0])) {
                $record_count = $list[0][0];
            }
            db_free($rs);
        }

        $tip1 = GetLangData("save");
        $image1 = "button/save.gif";
        $link1 = "javascript:save_message()";
        $tip2 = GetLangData("reset");
        $image2 = "button/reset.gif";
        $link2 = "javascript:reset_message()";
        if (strtolower(strGet("mode")) != "add") {
            $page_count = PageCount($record_count, $pagesize);
            $this->_pagenumber = PageNumber(strGet("page"), $page_count);
            if ($this->_pagenumber > 1) {
                $tip1 = sprintf(GetLangData("guestbook_previous"), $this->_pagenumber - 1, $page_count, $record_count);
                $image1 = "button/back.gif";
                $link1 = "guestbook.php?page=" . ($this->_pagenumber - 1);
            } else {
                $tip1 = sprintf(GetLangData("guestbook_first"), $record_count);
                $image1 = "button/back2.gif";
                $link1 = "";
            }
            if ($this->_pagenumber < $page_count) {
                $tip2 = sprintf(GetLangData("guestbook_next"), $this->_pagenumber + 1, $page_count, $record_count);
                $image2 = "button/next.gif";
                $link2 = "guestbook.php?page=" . ($this->_pagenumber + 1);
            } else {
                $tip2 = sprintf(GetLangData("guestbook_last"), $record_count);
                $image2 = "button/next2.gif";
                $link2 = "";
            }
        }

        $image1 = GetThemeResPath($image1, "images");
        $image2 = GetThemeResPath($image2, "images");
        if ($link1 != "")
            $link1 = "<a href=\"$link1\">";
        if ($link2 != "")
            $link2 = "<a href=\"$link2\">";

        $html = TransformTpl("guestbook_controlbox", array(
            "Image_View" => GetThemeResPath("button/look.gif", "images"),
            "Image_Write" => GetThemeResPath("button/write.gif", "images"),
            "Image_B1" => $image1,
            "Image_B2" => $image2,
            "Tip_B1" => $tip1,
            "Tip_B2" => $tip2,
            "LinkStart_B1" => $link1,
            "LinkEnd_B1" => $link1 != "" ? "</a>" : "",
            "LinkStart_B2" => $link2,
            "LinkEnd_B2" => $link2 != "" ? "</a>" : ""
                ), __CLASS__);

        $this->SetHeight("auto");
        $this->SetTitle(GetLangData("guestbook"));
        $this->SetContent($html, "center", "middle", 2);
    }

}
