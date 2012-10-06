<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require_once("content_admin.module.php");

class Media_Admin extends Content_Admin {

    function __construct(Passport &$passport) {
        $this->_passport = &$passport;
    }

    private function _check_fields() {

        $error = "";

        $media_add = trim(strPost("media_add"));
        if ($media_add == "") {
            $error .= "媒体地址不能为空;";
        } else if (strpos($media_add, chr(34))) {
            $error .= "媒体地址包含非法字符“\"”;";
        } else if (strlen($media_add) > 255) {
            $error .= "媒体地址过长（255字符以内）;";
        } else {
            $this->media_add = $media_add;
        }

        $this->media_name = strPost("media_name");
        $error.=CheckText("媒体名称", 255, 1, $this->media_name, FALSE, FALSE);

        if (strPost("media_HTML") != "yes") {
            $this->media_HTML = "false";
            $ishtml = FALSE;
        } else {
            $this->media_HTML = "true";
            $ishtml = TRUE;
        }
        $this->media_text = strPost("media_text");
        $error.=CheckText("媒体简介", 524288, 1, $this->media_text, $ishtml, TRUE, "无");

        $this->media_time = date("Y-m-j H:i:s");

        if ($error != "") {
            $this->error.=$error;
            return FALSE;
        }
        return TRUE;
    }

    protected function _check_add() {

        if (!$this->_check_fields())
            return FALSE;

        return $this->_check_parentclass("parent_class", 3);
    }

    public function add() {
        if ($this->task == "add") {
            if (!db_query("insert into media_info (
					media_HTML,
					visitor_count,
					parent_class,
					media_name,
					media_add,
					media_text,
					media_time
					) values (\"%s\",0,%d,\"%s\",\"%s\",\"%s\",\"%s\")", array(
                        $this->media_HTML,
                        $this->class_id,
                        $this->media_name,
                        $this->media_add,
                        $this->media_text,
                        $this->media_time
                    ))) {
                $this->error.="添加失败;";
            }
        }
    }

    protected function _check_save() {

        if (!$this->_check_fields())
            return FALSE;
        //Name has already got in _check_fields()
        return $this->_get_parentclass("id", 3, $this->media_id, $media_name);
    }

    public function save() {
        if ($this->task == "save") {
            if (!db_query("update media_info set
					media_HTML=\"%s\",
					media_time=\"%s\",
					media_name=\"%s\",
					media_add=\"%s\",
					media_text=\"%s\" 
					where id=%d", array(
                        $this->media_HTML,
                        $this->media_time,
                        $this->media_name,
                        $this->media_add,
                        $this->media_text,
                        $this->media_id
                    ))) {
                $this->error.="保存失败;";
            }
        }
    }

    protected function _check_delete() {
        return $this->_check_content("id", "media", $this->media_id, $this->media_name);
    }

    public function delete() {
        if ($this->task == "delete") {
            return $this->_delete("media", $this->media_id);
        }
        return FALSE;
    }

    protected function _check_recycle() {
        return $this->_check_content("id", "media", $this->media_id, $this->media_name);
    }

    public function recycle() {
        if ($this->task == "recycle") {
            return $this->_recycle("media", $this->media_id);
        }
        return FALSE;
    }

    //protected function _check_restore(){
    //	$check=$this->_check_content("id", "media", $this->media_id, $this->media_name);
    //	if(!$check) return FALSE;
    //	return $this->_check_parentclass("parent_class", 3);
    //}
    public function restore() {
        //	if($this->task=="restore"){
        $this->media_id = intval(strPost("contenttomove"));
        $this->class_id = intval(strPost("newclass"));
        return $this->_restore("media", $this->media_id, $this->class_id);
        //	}
        //	return FALSE;
    }

    protected function _check_move() {
        //get info of the new parent
        if ($this->_check_parentclass("newclass", 3)) {
            $this->new_parent_id = $this->class_id;
            $this->new_parent_name = $this->class_name;
            //get info of the old parent and self
            if ($this->_get_parentclass("contenttomove", 3, $this->media_id, $this->media_name)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function move() {
        if ($this->task == "move") {
            if ($this->_move(3, $this->media_id, $this->new_parent_id)) {
                $this->class_id = $this->new_parent_id;
                $this->class_name = $this->new_parent_name;
                return TRUE;
            }
        }
        return FALSE;
    }

}
