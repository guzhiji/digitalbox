<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class FriendSite_Admin {

    var $_passport, $error, $task;

    function __construct(Passport &$passport) {
        $this->_passport = &$passport;
    }

    public function check($function = NULL) {
        $this->error = "";
        if (!$this->_passport->check()) {
            $this->error.="权限不够;";
            return FALSE;
        }
        if ($function === NULL)
            $function = strGet("function");
        switch (strtolower($function)) {
            case "add":
                $this->task = ($this->_check_add()) ? "add" : "";
                break;
            case "save":
                $this->task = ($this->_check_save()) ? "save" : "";
                break;
            case "delete":
                $this->task = ($this->_check_delete()) ? "delete" : "";
                break;
            default:
                $this->task = "";
                $this->error .= "任务调度错误;";
        }
        return ($this->error == "");
    }

    private function _check_fields() {
        $error = "";

        $this->site_name = strPost("site_name");
        $error.=CheckText("网站名称", 255, 0, $this->site_name);

        $this->site_add = strPost("site_add");
        if (strlen($this->site_add) <= 10)
            $error .= "网站地址不正确;";
        else if (strlen($this->site_add) > 255)
            $error .= "网站地址过长;";
        //else if(strpos($this->site_add, Chr(34)) > 0)
        //$error .= "网站地址中有非法字符“\"”;";

        $this->site_logo = strPost("site_logo");
        if (strlen($this->site_logo) <= 7)
            $error .= "logo地址不正确;";
        else if (strlen($this->site_logo) > 255)
            $error .= "logo地址过长;";
        //else if(strpos($this->site_logo, Chr(34)) > 0)
        //$error .= "logo地址中有非法字符“\"”;";

        $this->site_text = strPost("site_text");
        $error.=CheckText("网站简介", 255, 0, $this->site_text, FALSE, FALSE, "无");

        if ($error != "") {
            $this->error.=$error;
            return FALSE;
        }
        return TRUE;
    }

    private function _check_add() {
        return $this->_check_fields();
    }

    public function add() {
        if ($this->task == "add") {
            return db_query("insert into friendsite_info (site_name,site_add,site_logo,site_text) values (\"%s\",\"%s\",\"%s\",\"%s\")", array($this->site_name, $this->site_add, $this->site_logo, $this->site_text));
        }
        return FALSE;
    }

    private function _check_delete() {
        $f = FALSE;
        $site_id = strPost("id");
        if ($site_id != "") {
            $rs = db_query("select site_name from friendsite_info where id=%d", array($site_id));
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    $this->site_name = $list[0]["site_name"];
                    $this->site_id = intval($site_id);
                    $f = TRUE;
                }
                db_free($rs);
            }
        }
        if (!$f) {
            $this->error .= "找不到删除对象;";
        }
        return $f;
    }

    public function delete() {
        if ($this->task == "delete") {
            return db_query("delete from friendsite_info where id=%d", array($this->site_id));
        }
        return FALSE;
    }

    private function _check_save() {
        $f = FALSE;
        $site_id = strPost("id");
        if ($site_id != "") {
            $rs = db_query("select id from friendsite_info where id=%d", array($site_id));
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    $this->site_id = intval($site_id);
                    $f = TRUE;
                }
                db_free($rs);
            }
        }
        if (!$f) {
            $this->error .= "找不到此链接;";
        } else {
            $f = $this->_check_fields();
        }
        return $f;
    }

    public function save() {
        if ($this->task == "save") {
            return db_query("update friendsite_info set site_name=\"%s\",site_add=\"%s\",site_logo=\"%s\",site_text=\"%s\" where id=%d", array($this->site_name, $this->site_add, $this->site_logo, $this->site_text, $this->site_id));
        }
        return FALSE;
    }

}
