<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Comment {

    var $error;
    var $name, $mail, $homepage, $head, $title, $text;

    public function check() {
        @session_start();
        $this->error = "";

        if (strSession("CheckCode") != strPost("check_code")) {
            $this->error.="验证码错误;";
        } else if (time() - intval(strSession("GuestBook_Time")) < 600) {
            $this->error .= "您已经留过言，请您在10分钟之后再留言。;";
        } else {

            $this->name = strPost("guest_name");
            $this->mail = strPost("guest_mail");
            $this->homepage = strPost("guest_homepage");
            $this->head = strPost("guest_head");
            $this->title = strPost("guest_title");
            $this->text = strPost("guest_text");

            $this->error .= CheckText("姓名", 20, 1, $this->name);

//			if(strlen($this->mail) > 200){
//				$this->error .= "您的电子邮箱地址过长;";
//			}else if(strlen($this->mail) > 5){
//				if(strpos($this->mail, "@") == 0 || strpos($this->mail,".") == 0) $this->error .= "您填写的是错误的电子邮箱地址;";
//			}
            if ($this->mail == "")
                $this->error .= "请提供您的电子邮箱地址;";
            else if (strlen($this->mail) > 200)
                $this->error .= "您的电子邮箱地址过长;";
            else if (!preg_match("/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$/i", $this->mail))
                $this->error .= "您填写的是错误的电子邮箱地址;";

            $this->error .= CheckText("主题", 200, 1, $this->title);

            if (strlen($this->head) == 0) {
                $this->error .= "请选择头像;";
            } else if (intval($this->head) > 23 || intval($this->head) < 1) {
                $this->error .= "没有您选的头像;";
            }

            $this->error .= CheckText("留言", 5000, 5, $this->text, FALSE, TRUE);

            if (strlen($this->homepage) > 7) {
                if (strlen($this->homepage) > 200)
                    $this->error .= "您的主页地址过长;";
//                else if (!preg_match("/(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/", $this->homepage))
//                    $this->error .= "您的主页地址是错误的;";
            }
        }
        return ($this->error == "");
    }

    public function add() {

        if ($this->check()) {

            if (db_query("insert into guest_info (
			guest_IP,guest_date,guest_name,guest_mail,guest_homepage,guest_head,guest_title,guest_text
			) values (\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",%d,\"%s\",\"%s\")", array($_SERVER["REMOTE_ADDR"], date("Y-m-j H:i:s"), $this->name, $this->mail, $this->homepage, $this->head, $this->title, $this->text))
            ) {
                if (function_exists('session_is_registered') && !session_is_registered(dbPrefix . "_GuestBook_Time")) {
                    session_register(dbPrefix . "_GuestBook_Time");
                }
                $_SESSION[dbPrefix . "_GuestBook_Time"] = time();
                return TRUE;
            }
        }
        return FALSE;
    }

}
