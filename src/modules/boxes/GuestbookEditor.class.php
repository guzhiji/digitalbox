<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class GuestbookEditor extends Box {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {
        $html = TransformTpl("guestbook_editor", array(
            "Editor_Title" => str_replace(chr(34), "&quot;", urldecode(strGet("reply")))
                ), __CLASS__);

        $this->SetHeight("auto");
        $this->SetTitle(GetLangData("guestbook_msg"));
        $this->SetContent($html, "center", "middle", 2);
    }

}
