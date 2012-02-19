<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Channel_Admin {

    var $_passport, $error, $task;
    var $channel_id, $channel_name, $channel_type, $channel_add;

    function __construct(Passport &$passport) {
        $this->_passport = &$passport;
    }

    public function check($function = NULL) {
        $this->error = "";
        if (!$this->_passport->isMaster()) {
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
        $this->error = "";

        $this->channel_name = strPost("channel_name");
        $this->channel_add = strPost("channel_add");

        $this->error .= CheckText("频道名", 20, 1, $this->channel_name);

        if ($this->channel_type > 4 || $this->channel_type < 0) {
            $this->error .= "没有此类型;";
        } else if ($this->channel_type == 0) {
            if ($this->channel_add == "") {
                $this->error .= "频道地址为空;";
            } else {
                if (strlen($this->channel_add) > 255)
                    $this->error .= "频道地址不可超过255字符;";
            }
        }

        return ($this->error == "");
    }

    private function _check_add() {
        $this->channel_type = intval(strPost("channel_type"));
        return $this->_check_fields();
    }

    public function add() {
        $f = FALSE;
        if ($this->task == "add") {
            if ($this->channel_add != "") {
                $sql = "INSERT INTO channel_info (channel_name,channel_type) VALUES (\"%s\",%d)";
                $f = db_query($sql, array($this->channel_name, $this->channel_type));
            } else {
                $sql = "INSERT INTO channel_info (channel_name,channel_type,channel_add) VALUES (\"%s\",%d,\"%s\")";
                $f = db_query($sql, array($this->channel_name, $this->channel_type, $this->channel_add));
            }
        }
        if (!$f)
            $this->error.="添加失败;";
        return $f;
    }

    private function _check_save() {
        $f = FALSE;
        $channel_id = strPost("id");
        if ($channel_id != "") {
            $rs = db_query("select channel_type from channel_info where id=%d", array($channel_id));
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    $this->channel_type = $list[0]["channel_type"];
                    $this->channel_id = intval($channel_id);
                    $f = TRUE;
                }
                db_free($rs);
            }
        }
        if ($f) {
            $f = $this->_check_fields();
        } else {
            $this->error .= "找不到此频道;";
        }
        return $f;
    }

    public function save() {
        $f = FALSE;
        if ($this->task == "save") {
            if ($this->channel_type == 0) {
                $sql = "UPDATE channel_info SET channel_name=\"%s\",channel_add=\"%s\" WHERE id=%d";
                $f = db_query($sql, array($this->channel_name, $this->channel_add, $this->channel_id));
            } else {
                $sql = "UPDATE channel_info SET channel_name=\"%s\" WHERE id=%d";
                $f = db_query($sql, array($this->channel_name, $this->channel_id));
            }
            if (!$f)
                $this->error.="保存失败;";
        }
        return $f;
    }

    private function _check_delete() {
        $f = FALSE;

        $channel_id = trim(strPost("id"));
        if ($channel_id != "") {
            $rs = db_query("select id,channel_name,channel_type from channel_info where id=%d", array($channel_id));
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    $this->channel_id = intval($channel_id);
                    $this->channel_name = $list[0]["channel_name"];
                    $this->channel_type = $list[0]["channel_type"];
                    $f = TRUE;
                }
                db_free($rs);
            }
        }
        if (!$f) {
            $this->error .= "找不到该频道;";
            return $f;
        }

        $rs = db_query("select id from class_info where parent_channel=%d", array($channel_id));
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0])) {
                $this->error.="不能删除非空频道;";
                $f = FALSE;
            }
            db_free($rs);
        }

        return $f;
    }

    public function delete() {
        $f = FALSE;
        if ($this->task == "delete") {
            $f = db_query("DELETE FROM channel_info WHERE id=%d", array($this->channel_id));
        }
        if (!$f)
            $this->error.="删除失败;";
        return $f;
    }

}