<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/Passport.class.php
  ------------------------------------------------------------------
 */

class Passport {

    var $_connid, $error;

    function __construct($connid=NULL) {
        $this->_connid = $connid;
        @session_start();
    }

    public static function PWDEncrypt($password) {
        return md5(strrev("DigitalBoxV2.x" . md5($password) . "Security"));
    }

    public static function ExportCheckCodeImage() {

        $checkcode = "";
        $len = 5;
        $font_size = 5;
        $fs_min = 2;
        $width = imagefontwidth($font_size) * $len;
        $height = imagefontheight($font_size) * 2;

        $img = imagecreate($width, $height);
        $bg = imagecolorallocate($img, 225, 225, 225);

        for ($i = 0; $i < $len; $i++) {
            //random color
            $fcolor = imagecolorallocate($img, rand(0, 150), rand(0, 150), rand(0, 150));
            //random size
            $tsize = rand($fs_min, $font_size);
            //random position
            $ypos = rand(0, imagefontwidth($font_size));
            $xpos = $i * imagefontwidth($font_size);
            //random code
            $code = rand(0, 9);
            imagechar($img, $tsize, $xpos, $ypos, $code, $fcolor);
            $checkcode.=$code;
        }

        //save check code
        @session_start();
        if (!session_is_registered(dbPrefix . "_CheckCode"))
            session_register(dbPrefix . "_CheckCode");
        $_SESSION[dbPrefix . "_CheckCode"] = $checkcode;

        //export
        header("Content-Type: image/gif");
        imagegif($img);
        imagedestroy($img);
    }

    public function setConn(&$connid) {
        $this->_connid = &$connid;
    }

    public function check() {
        return session_is_registered(dbPrefix . "_Admin_UID") && strSession("Admin_UID") != "";
    }

    public function doubleCheck($ismaster=TRUE) {
        if ($this->_connid === NULL) {
            $this->error.="没有连接数据库;";
            return FALSE;
        }
        if (!$this->check()) {
            $this->error.="还没有登陆;";
            return FALSE;
        }
        $username = strSession("Admin_UID");
        $password = self::PWDEncrypt(strPost("password"));
        if ($username == strSession("Admin_UID")) {
            $rs = db_query($this->_connid, "SELECT \"true\" FROM admin_info WHERE admin_UID=\"%s\" AND admin_PWD=\"%s\"", array($username, $password));
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    if ($list[0][0] == "true") {
                        return $ismaster ? $this->isMaster() : TRUE;
                    }
                }
                db_free($rs);
            }
        }
        $this->error.="您没有足够权限;";
        return FALSE;
    }

    public function isMaster() {
        if (strSession("Admin_UID") == GetSettingValue("master_name") || strSession("Admin_UID") == "")
            return TRUE;
        return FALSE;
    }

    public function login() {
        if ($this->_connid === NULL) {
            $this->error.="没有连接数据库;";
            return FALSE;
        }
        $username = strPost("username");
        $password = strPost("password");
        if ($username != "" && $password != "") {
            $password = self::PWDEncrypt($password);
            $failureTime = intval(strSession("LoginFailures"));
            if ($failureTime > 5) {
                $this->error.="您尝试登陆次数过多;";
                return FALSE;
            }
            if (strSession("CheckCode") != strPost("checkcode")) {
                $this->error.="验证码错误;";
            } else {
                $rs = db_query($this->_connid, "SELECT \"true\" FROM admin_info WHERE admin_UID=\"%s\" AND admin_PWD=\"%s\"", array($username, $password));
                if ($rs) {
                    $list = db_result($rs);
                    if (isset($list[0])) {
                        if ($list[0][0] == "true") {
                            if (session_is_registered(dbPrefix . "_LoginFailures"))
                                $_SESSION[dbPrefix . "_LoginFailures"] = 0;
                            if (!session_is_registered(dbPrefix . "_Admin_UID"))
                                session_register(dbPrefix . "_Admin_UID");
                            $_SESSION[dbPrefix . "_Admin_UID"] = $username;
                            return TRUE;
                        }
                    }
                    db_free($rs);
                }
                $this->error.="用户名或密码错误;";
            }
            if (!session_is_registered(dbPrefix . "_LoginFailures"))
                session_register(dbPrefix . "_LoginFailures");
            $_SESSION[dbPrefix . "_LoginFailures"] = $failureTime + 1;
        }else {
            $this->error.="未填写完整;";
        }
        return FALSE;
    }

    public function logout() {
        if (session_is_registered(dbPrefix . "_Admin_UID"))
            session_destroy();
    }

}

?>