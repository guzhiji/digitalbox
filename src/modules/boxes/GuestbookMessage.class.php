<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class GuestbookMessage extends Box {

    protected $_item;

    function __construct($dataitem) {
        parent::__construct("Left", "box3");
        $this->_item = $dataitem;
    }

    public function DataBind() {

        $homepage = "";
        if (strlen($this->_item["guest_homepage"]) > 7) {
            $hplink = htmlspecialchars($this->_item["guest_homepage"]);
            $homepage = "<a target=\"_blank\" href=\"{$hplink}\"><img title=\"{$hplink}\" border=0 src=\"" . GetThemeResPath("url.gif", "images") . "\" /></a>";
        } else {
            $homepage = "<img title=\"" . GetLangData("notfilled") . "\" border=\"0\" src=\"" . GetThemeResPath("nourl.gif", "images") . "\" />";
        }

        $mail = "";
        if (strlen($this->_item["guest_mail"]) > 5) {
            $mail = "<img border=\"0\" src=\"" . GetThemeResPath("email.gif", "images") . "\" />";
        } else {
            $mail = "<img title=\"" . GetLangData("notfilled") . "\" border=\"0\" src=\"" . GetThemeResPath("noemail.gif", "images") . "\" />";
        }

        $replylink = "guestbook.php?mode=add&reply=" . urlencode("re:" . GetLangData("guestbook_msg") . "《" . str_replace("《", "〈", str_replace("》", "〉", $this->_item["guest_title"])) . "》");

        $html = TransformTpl("guestbook_message", array(
            "Head" => "images/head/" . $this->_item["guest_head"] . ".gif",
            "Name" => $this->_item["guest_name"],
            "Text" => $this->_item["guest_text"],
            "Homepage" => $homepage,
            "Mail" => $mail,
            "Link_Reply" => htmlspecialchars($replylink),
            "Image_Reply" => GetThemeResPath("reply.gif", "images"),
            "Image_Time" => GetThemeResPath("time.gif", "images"),
            "Date" => $this->_item["guest_date"]
                ), __CLASS__);

        $this->SetHeight("auto");
        $this->SetTitle($this->_item["guest_title"]);
        $this->SetContent($html, "center", "middle", 2);
    }

}
