<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/data/vote_admin.module.php
  ------------------------------------------------------------------
 */

class Vote_Admin {

    var $_connid, $_passport, $error, $task;

    function __construct($connid, Passport &$passport) {
        $this->_connid = $connid;
        $this->_passport = &$passport;
    }

    public function check($function=NULL) {
        $this->error = "";
        if (!$this->_passport->check()) {
            $this->error .= "您没有足够权限;";
            return FALSE;
        }
        if ($function === NULL)
            $function = strGet("function");
        switch (strtolower($function)) {
            case "start":
                $this->task = "start";
                break;
            case "stop":
                $this->task = "stop";
                break;
            case "add":
                $this->task = ($this->_check_add()) ? "add" : "";
                break;
            case "delete":
                $this->task = "delete";
                break;
            case "set":
                $this->task = ($this->_check_set()) ? "set" : "";
                break;
            default:
                $this->task = "";
                $this->error .= "任务调度错误;";
        }
        return ($this->error == "");
    }

    public function start() {
        $f = FALSE;
        if ($this->task == "start") {
            $rs = db_query($this->_connid, "select id from vote_info");
            if ($rs) {
                $list = db_result($rs);

                if (count($list) > 1) {
//					db_query($this->_connid,"update setting_info set setting_value=\"true\" where setting_name=\"vote_visible\"");
//					db_query($this->_connid,"update setting_info set setting_value=\"true\" where setting_name=\"vote_on\"");
                    db_query($this->_connid, "update vote_info set vote_value=0");
                    $f = SaveSettings($this->_connid, array(
                        "vote_visible" => TRUE,
                        "vote_on" => TRUE
                            ));
                    if (!$f)
                        $this->error .= "保存中出现意外错误;";
                }else {
                    $this->error .= "您还未设置足够投票项目;";
                }
                db_free($rs);
            }
        }
        return $f;
    }

    public function stop() {
        $f = FALSE;
        if ($this->task == "stop") {
            $f = SaveSettings($this->_connid, array(
                "vote_on" => FALSE
                    ));
            if (!$f)
                $this->error .= "发生意外错误;";
//            $f = db_query($this->_connid, "update setting_info set setting_value=\"false\" where setting_name=\"vote_on\"");
//            RefreshSettings($this->_connid);
        }
        return $f;
    }

    private function _check_add() {
        $this->vote_name = strPost("vote_name");
        $error = CheckText("项目名称", 20, 1, $this->vote_name);
        $this->error .= $error;
        return ($error == "");
    }

    public function add() {
        if ($this->task == "add") {
            if (db_query($this->_connid, "insert into vote_info (vote_name,vote_value) values (\"%s\",0)", array($this->vote_name))) {
                return TRUE;
            }
            $this->error .= "发生意外错误;";
        }
        return FALSE;
    }

    public function delete() {
        if ($this->task == "delete") {
            $id = intval(strPost("id"));
            if (db_query($this->_connid, "delete from vote_info where id=%d", array($id))) {
                return TRUE;
            }
            $this->error .= "删除失败;";
        }
        return FALSE;
    }

    private function _check_set() {
        $this->vote_desc = strPost("vote_description");
        $e = CheckText("投票描述", 250, 0, $this->vote_desc, FALSE, FALSE, "");
        $this->error .= $e;
        return ($e == "");
    }

    public function set() {
        if ($this->task == "set") {
            if (SaveSettings($this->_connid, array("vote_description" => $this->vote_desc)))
                return TRUE;
//            if (db_query($this->_connid, "update setting_info set setting_value=\"%s\" where setting_name=\"vote_description\"", array($this->vote_desc))) {
//                return TRUE;
//            }
            $this->error .= "发生意外错误;";
        }
        return FALSE;
    }

}

?>