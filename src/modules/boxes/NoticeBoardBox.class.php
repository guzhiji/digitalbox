<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class NoticeBoardBox extends BoxModel {

    function __construct() {
        parent::__construct("Right", "box1");
        if (!GetSettingValue("notice_visible"))
            $this->_status = 2;
    }

    public function DataBind() {
        $html = "<marquee onmouseover=\"this.stop();\" onmouseout=\"this.start();\" scrollamount=\"2\" scrolldelay=\"100\" direction=\"up\" width=\"150\" height=\"100\" align=\"left\">" . htmlspecialchars(GetSettingValue("notice_text")) . "</marquee>";
        $this->SetHeight("auto");
        $this->SetTitle(GetLangData("notice"));
        $this->SetContent($html, "center", "middle", 10);
    }

}
