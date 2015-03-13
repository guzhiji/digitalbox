<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class MsgBox extends Box {

    private $msg = "";
    private $title = NULL;
    private $back = NULL;

    function __construct($msg, $title = NULL, $back = NULL) {
        parent::__construct("Left", "box3");
        $this->msg = $msg;
        $this->title = $title;
        $this->back = $back;
    }

    public function DataBind() {
        $html = $this->msg;
        if ($this->back != NULL) {
            $back = "history.back(1);";
            if ($this->back != "back")
                $back = "location.href='{$this->back}'";
            $html = TransformTpl("msg", array(
                "msg" => $html,
                "back" => $back
                    ), __CLASS__);
        }
        $this->SetHeight("auto");
        $this->SetTitle($this->title == NULL ? "" : $this->title);
        $this->SetContent($html, "center", "middle", 2);
    }

}