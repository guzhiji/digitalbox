<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_ClearRecyclebin extends Box {

    private $type;

    function __construct($type) {
        parent::__construct("Left", "box3");
        $this->type = $type;
    }

    public function DataBind() {
        $type_cn = GetTypeName($this->type, 0);

        $this->SetHeight("auto");
        $this->SetTitle("清空回收站");
        $this->SetContent(TransformTpl("recyclebin_clearconfirm", array(
                    "type" => $this->type,
                    "type_cn" => $type_cn
                        ), __CLASS__), "center", "middle", 30);
    }

}
