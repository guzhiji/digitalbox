<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class User_Admin {

    var $_passport, $error, $task;
    var $new_pwd, $admin_uid, $admin_pwd;

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
            case "delete":
                $this->task = ($this->_check_delete()) ? "delete" : "";
                break;
            case "changepwd":
                $this->task = ($this->_check_changepwd()) ? "changepwd" : "";
                break;
            default:
                $this->task = "";
                $this->error = "任务调度错误;";
        }
        return ($this->error == "");
    }

    private function _check_changepwd() {
        $password = strPost("new_PWD");
        $repeat = strPost("copy_new");
        $result = self::PWDCheck($password, $repeat, strSession("Admin_UID"));
        if ($result == "") {

            if ($this->_passport->doubleCheck(FALSE)) {

                $this->new_pwd = Passport::PWDEncrypt($new_PWD);
                return TRUE;
            } else {
                $this->error.=$this->_passport->error;
            }
        } else {
            $this->error.=$result;
        }

        return FALSE;
    }

    public function changePWD() {
        if ($this->task == "changepwd") {
            return db_query("update admin_info set admin_PWD=\"%s\" where admin_UID=\"%s\"", array($this->new_pwd, strSession("Admin_UID")));
        }
        return FALSE;
    }

    private function _check_add() {

        $this->error = "";
        if ($this->_passport->doubleCheck(TRUE)) {

            $admin_uid = strPost("UID");
            $admin_pwd = strPost("PWD");
            $check_pwd = strPost("PWD_check");

            $this->error .= self::UIDCheck($admin_uid);
            $this->error .= self::PWDCheck($admin_pwd, $check_pwd, $admin_uid);

            if ($this->error == "") {
                $rs = db_query("select admin_UID from admin_info where admin_UID=\"%s\"", array($admin_uid));
                if ($rs) {
                    $list = db_result($rs);
                    if (isset($list[0])) {
                        $this->error .= "此用户名已经存在;";
                    }
                    db_free($rs);
                }
            }

            if ($this->error == "") {
                $this->admin_uid = $admin_uid;
                $this->admin_pwd = Passport::PWDEncrypt($admin_pwd);
                return TRUE;
            }
        } else {
            $this->error .= "您的权限不够，请与站长联系;";
        }
        return FALSE;
    }

    public function add() {
        if ($this->task == "add") {
            return db_query("insert into admin_info (admin_UID,admin_PWD) values (\"%s\",\"%s\")", array($this->admin_uid, $this->admin_pwd));
        }
        return FALSE;
    }

    private function _check_delete() {

        $this->error = "";
        $admin_uid = strPost("UID");

        if ($admin_uid != "") {

            if ($this->_passport->doubleCheck(TRUE)) {

                if (strtolower($admin_uid) == strtolower(GetSettingValue("master_name"))) {
                    $this->error .= "站长不能被删除;";
                } else {
                    $rs = db_query("select admin_UID from admin_info where admin_UID=\"%s\"", array($admin_uid));
                    if ($rs) {
                        $list = db_result($rs);
                        if (!isset($list[0])) {
                            $this->error .= "找不到此管理员;";
                        }
                        db_free($rs);
                    }
                }

                if ($this->error == "") {
                    $this->admin_uid = $admin_uid;
                    return TRUE;
                }
            } else {
                $this->error .= "您的权限不够，请与站长联系;";
            }
        } else {
            $this->error .= "您没有选中要删除的管理员;";
        }

        return FALSE;
    }

    public function delete() {
        if ($this->task == "delete") {
            return db_query("delete from admin_info where admin_UID=\"%s\"", array($this->admin_uid));
        }
        return FALSE;
    }

    public static function UIDCheck($uid) {

        $error = "";
        if (!preg_match("/^[a-z0-9_\.\-]{3,20}$/i", $uid)) {
            $l = strlen($uid);
            if ($l < 3 || $l > 20) {
                $error .= "用户名应大于2字符，小于或等于20字符;";
            } else {
                $error .= "用户名中包含非法字符;";
            }
        }
        return "";
    }

    public static function PWDCheck($password, $repeat, $username = "") {

        $error = "";
        if (!preg_match("/^[a-z0-9]{6,50}$/i", $password)) {
            $l = strlen($password);
            if ($l < 6 || $l > 50) {
                $error .= "密码应大于5字符，并小于或等于50字符;";
            } else {
                $error .= "密码中包含非法字符;";
            }
        }

        if ($repeat != $password)
            $error .= "密码确认与新密码不一样;";

        if ($username != "") {
            if ($username == $password)
                $error .= "安全起见，用户名与密码不应相同;";
        }

        return $error;
    }

}