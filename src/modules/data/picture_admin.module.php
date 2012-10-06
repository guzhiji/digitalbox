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

class Picture_Admin extends Content_Admin {

    function __construct(Passport &$passport) {
        $this->_passport = &$passport;
    }

    private function _check_fields() {

        $error = "";

        $picture_add = trim(strPost("picture_add"));
        if ($picture_add == "") {
            $error .= "图片地址不能为空;";
        } else if (strpos($picture_add, chr(34))) {
            $error .= "图片地址包含非法字符“\"”;";
        } else if (strlen($picture_add) > 255) {
            $error .= "图片地址过长（255字符以内）;";
        } else {
            $this->picture_add = $picture_add;
        }

        $this->picture_name = strPost("picture_name");
        $error.=CheckText("图片名称", 255, 1, $this->picture_name, FALSE, FALSE);

        if (strPost("picture_HTML") != "yes") {
            $this->picture_HTML = "false";
            $ishtml = FALSE;
        } else {
            $this->picture_HTML = "true";
            $ishtml = TRUE;
        }
        $this->picture_text = strPost("picture_text");
        $error.=CheckText("图片简介", 524288, 1, $this->picture_text, $ishtml, TRUE, "无");

        $this->picture_time = date("Y-m-j H:i:s");

        if ($error != "") {
            $this->error.=$error;
            return FALSE;
        }
        return TRUE;
    }

    protected function _check_add() {

        if (!$this->_check_fields())
            return FALSE;

        return $this->_check_parentclass("parent_class", 2);
    }

    public function add() {
        if ($this->task == "add") {
            if (!db_query("insert into picture_info (
					picture_HTML,
					visitor_count,
					parent_class,
					picture_name,
					picture_add,
					picture_text,
					picture_time
					) values (\"%s\",0,%d,\"%s\",\"%s\",\"%s\",\"%s\")", array(
                        $this->picture_HTML,
                        $this->class_id,
                        $this->picture_name,
                        $this->picture_add,
                        $this->picture_text,
                        $this->picture_time
                    ))) {
                $this->error.="添加失败;";
            }
        }
    }

    protected function _check_save() {

        if (!$this->_check_fields())
            return FALSE;
        //Name has already got in _check_fields()
        return $this->_get_parentclass("id", 2, $this->picture_id, $picture_name);
    }

    public function save() {
        if ($this->task == "save") {
            if (!db_query("update picture_info set
					picture_HTML=\"%s\",
					picture_name=\"%s\",
					picture_add=\"%s\",
					picture_text=\"%s\",
					picture_time=\"%s\" 
					where id=%d", array(
                        $this->picture_HTML,
                        $this->picture_name,
                        $this->picture_add,
                        $this->picture_text,
                        $this->picture_time,
                        $this->picture_id
                    ))) {
                $this->error.="保存失败;";
            }
        }
    }

    protected function _check_delete() {
        return $this->_check_content("id", "picture", $this->picture_id, $this->picture_name);
    }

    public function delete() {
        if ($this->task == "delete") {
            return $this->_delete("picture", $this->picture_id);
        }
        return FALSE;
    }

    protected function _check_recycle() {
        return $this->_check_content("id", "picture", $this->picture_id, $this->picture_name);
    }

    public function recycle() {
        if ($this->task == "recycle") {
            return $this->_recycle("picture", $this->picture_id);
        }
        return FALSE;
    }

    //protected function _check_restore(){
    //	$check=$this->_check_content("id", "picture", $this->picture_id, $this->picture_name);
    //	if(!$check) return FALSE;
    //	return $this->_check_parentclass("parent_class", 2);
    //}
    public function restore() {
        //	if($this->task=="restore"){
        $this->picture_id = intval(strPost("contenttomove"));
        $this->class_id = intval(strPost("newclass"));
        return $this->_restore("picture", $this->picture_id, $this->class_id);
        //	}
        //	return FALSE;
    }

    protected function _check_move() {
        //get info of the new parent
        if ($this->_check_parentclass("newclass", 2)) {
            $this->new_parent_id = $this->class_id;
            $this->new_parent_name = $this->class_name;
            //get info of the old parent and self
            if ($this->_get_parentclass("contenttomove", 2, $this->picture_id, $this->picture_name)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function move() {
        if ($this->task == "move") {
            if ($this->_move(2, $this->picture_id, $this->new_parent_id)) {
                $this->class_id = $this->new_parent_id;
                $this->class_name = $this->new_parent_name;
                return TRUE;
            }
        }
        return FALSE;
    }

}
