<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_ChPWD extends Box {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {
        $html = TransformTpl("account_changepwd", array(
            "Account_Username" => strSession("Admin_UID")
                ), __CLASS__);

        $this->SetHeight("auto");
        $this->SetTitle("修改密码");
        $this->SetContent($html, "center", "middle", 2);
    }

}
