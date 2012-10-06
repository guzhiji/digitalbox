<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_Guestbook extends BoxModel {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {
        require_once("modules/lists/CommentList.class.php");
        $cl = new CommentList("commentlist_admin_item");
        $cl->SetContainer("commentlist_admin", 2);
        $cl->Bind();

        $this->SetTitle("评论管理");
        $this->SetContent($cl->GetHTML(), "left", "middle", 10);
    }

}

?>
