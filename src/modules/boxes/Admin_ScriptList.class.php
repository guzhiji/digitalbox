<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_ScriptList extends Box {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {
        $title = "脚本设置";
        if (!is_dir("scripts/addons")) {
            //TODO show info
            $this->_status = 1;
            $this->_error = "找不到脚本目录（scripts/addons），可能是文件系统权限造成的";
            $this->_backpage = "admin_setting.php";
            $this->SetTitle($title);
        } else {
            $selected = explode(",", GetSettingValue("portal_scripts"));
            $scriptlist = new ListModel(__CLASS__, "scriptlist_item");
            $scriptlist->SetContainer("scriptlist_container");
            $d = dir("scripts/addons");
            while (FALSE !== ($script = $d->read())) {
                if (strtolower(substr($script, -3)) == ".js") {
                    $script = substr($script, 0, -3);
                    $s = "";
                    if (in_array($script, $selected))
                        $s = " checked=\"checked\"";
                    $scriptlist->AddItem(array(
                        "ScriptName" => $script,
                        "Checked" => $s
                    ));
                }
            }
            $d->close();

            $this->SetHeight("auto");
            $this->SetTitle($title);
            $this->SetContent($scriptlist->GetHTML(), "center", "middle", 2);
        }
    }

}
