<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Class_Admin {

    var $_passport, $error, $task;
    var $channel_id, $channel_name, $channel_type;
    var $class_id, $class_name;

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

        $this->class_name = strPost("class_name");
        $this->error .= CheckText("栏目名", 20, 1, $this->class_name);

        return ($this->error == "");
    }

    private function _check_add() {
        $e = TRUE;
        $f = FALSE;
        $this->channel_id = strPost("channel_id");
        if ($this->channel_id != "") {
            $rs = db_query("select id,channel_name,channel_type from channel_info where id=%d", array($this->channel_id));
            if ($rs) {
                $list = db_result($rs);
                if (!isset($list[0])) {
                    $e = FALSE;
                } else if ($list[0]["channel_type"] == 0) {
                    $this->error .= "自定义链接频道下不可建立栏目;";
                } else {
                    $this->channel_name = $list[0]["channel_name"];
                    $f = $this->_check_fields();
                }
                db_free($rs);
            } else {
                $e = FALSE;
            }
        } else {
            $e = FALSE;
        }
        if (!$e)
            $this->error .= "找不到此频道;";
        return $f;
    }

    public function add() {
        $f = FALSE;
        if ($this->task == "add") {
            $f = db_query("insert into class_info (class_name,parent_channel) values (\"%s\",%d)", array($this->class_name, $this->channel_id));
        }
        if (!$f)
            $this->error.="添加失败;";
        return $f;
    }

    private function _check_save() {
        $f = FALSE;
        $class_id = strPost("id");
        if ($class_id != "") {
            $rs = db_query("select
			parent_channel as channel_id,
			channel_type,
			channel_name,
			class_name 
			from channel_info,class_info 
			where class_info.parent_channel=channel_info.id 
			and channel_type!=0 
			and class_info.id=%d"
                    , array($class_id));

            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    $this->class_id = $class_id;
                    //$old_name = $list[0]["class_name"];
                    $this->channel_id = $list[0]["channel_id"];
                    $this->channel_name = $list[0]["channel_name"];
                    $f = TRUE;
                }
                db_free($rs);
            }
        }
        if ($f) {
            $f = $this->_check_fields();
        } else {
            $this->error .= "找不到此栏目;";
        }
        return $f;
    }

    public function save() {
        $f = FALSE;
        if ($this->task == "save") {
            $f = db_query("update class_info set class_name=\"%s\" where id=%d", array($this->class_name, $this->class_id));
        }
        if (!$f)
            $this->error.="保存失败;";
        return $f;
    }

    private function _check_delete() {
        $f = FALSE;
        $class_id = trim(strPost("id"));
        if ($class_id != "") {
            $rs = db_query("select channel_info.id as channel_id,channel_name,channel_type,class_name from class_info,channel_info where channel_info.id=class_info.parent_channel and class_info.id=%d", array($class_id));
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    $this->channel_id = $list[0]["channel_id"];
                    $this->channel_name = $list[0]["channel_name"];
                    $this->channel_type = $list[0]["channel_type"];
                    $this->class_name = $list[0]["class_name"];
                    $this->class_id = intval($class_id);
                    $f = TRUE;
                }
                db_free($rs);
            }
        }
        if ($f) {
            $class_type = GetTypeName($this->channel_type, 1);
            $rs = db_query("select id from {$class_type}_info where parent_class=%d", array($class_id));
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    $this->error.="不能删除非空栏目";
                    $f = FALSE;
                }
                db_free($rs);
            }
        } else {
            $this->error .= "找不到该栏目;";
        }
        return $f;
    }

    public function delete() {
        $f = FALSE;
        if ($this->task == "delete") {
            $f = db_query("delete from class_info where id=%d", array($this->class_id));
        }
        if (!$f)
            $this->error.="删除失败;";
        return $f;
    }

}
