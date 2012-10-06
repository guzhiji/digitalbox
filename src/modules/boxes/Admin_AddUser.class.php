<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_AddUser extends BoxModel {

    function __construct($args) {
        parent::__construct("Left");
        $this->_tplName = "box3";
    }

    public function DataBind() {

        $this->SetHeight("auto");
        $this->SetTitle("添加人员");
        $this->SetContent(GetTemplate("account_add", __CLASS__), "center", "middle", 2);
    }

}
