<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Comment_Admin {

    var $_passport;

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
            case "clear":
                $this->task = "clear";
                break;
            case "delete":
                $this->task = "delete";
                break;
            default:
                $this->task = "";
                $this->error .= "任务调度错误;";
        }
        return ($this->error == "");
    }

    public function clear() {

        if ($this->task == "clear" && $this->_passport->isMaster()) {
            if (db_query("DELETE FROM guest_info") &&
                    db_query("OPTIMIZE TABLE guest_info")) {
                return TRUE;
            } else {
                $this->error.="清空失败;";
            }
        } else {
            $this->error.="权限不够;";
        }
        return FALSE;
    }

    public function delete() {
        $e = FALSE;
        if ($this->task != "delete") {
            $e = TRUE;
        } else {
            $message_id = strPost("id");
            if ($message_id != "") {
                if (!db_query("delete from guest_info where id=%d", array($message_id))) {
                    $e = TRUE;
                }
            } else {
                $e = TRUE;
            }
        }
        if ($e)
            $this->error .= "删除失败;";
        return !$e;
    }

}
