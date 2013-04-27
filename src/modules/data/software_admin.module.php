<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require_once("content_admin.module.php");

class Software_Admin extends Content_Admin {

    function __construct(Passport &$passport) {
        $this->_passport = &$passport;
    }

    private function _check_fields() {

        $error = "";

        $software_add = trim(strPost("software_add"));
        if ($software_add == "") {
            $error .= "软件地址不能为空;";
        } else if (strpos($software_add, chr(34))) {
            $error .= "软件地址包含非法字符“\"”;";
        } else if (strlen($software_add) > 255) {
            $error .= "软件地址过长（255字符以内）;";
        } else {
            $this->software_add = $software_add;
        }

        $this->software_size = Size2Bytes(strPost("software_size"), strPost("size_unit"));

        $this->software_grade = intval(strPost("software_grade"));
        if ($this->software_grade > 5 || $this->software_grade < 1) {
            $error .= "推荐程度不正确;";
        }

        $this->software_name = strPost("software_name");
        $error.=CheckText("软件名称", 255, 1, $this->software_name, FALSE, FALSE);

        $this->software_type = strPost("software_type");
        $error.=CheckText("软件类型", 255, 1, $this->software_type, FALSE, FALSE);

        $this->software_producer = strPost("software_producer");
        $error.=CheckText("软件作者", 255, 1, $this->software_producer, FALSE, FALSE);

        $this->software_language = strPost("software_language");
        $error.=CheckText("软件语言", 255, 1, $this->software_language, FALSE, FALSE, "未知");

        $this->software_environment = strPost("software_environment");
        $error.=CheckText("软件环境", 255, 1, $this->software_environment, FALSE, FALSE, "未知");

        if (strPost("software_HTML") != "yes") {
            $this->software_HTML = "false";
            $ishtml = FALSE;
        } else {
            $this->software_HTML = "true";
            $ishtml = TRUE;
        }
        $this->software_text = strPost("software_text");
        $error.=CheckText("软件简介", 524288, 1, $this->software_text, $ishtml, TRUE, "无");

        $this->software_time = date("Y-m-j H:i:s");

        if ($error != "") {
            $this->error.=$error;
            return FALSE;
        }
        return TRUE;
    }

    protected function _check_add() {

        if (!$this->_check_fields())
            return FALSE;

        return $this->_check_parentclass("parent_class", 4);
    }

    public function add() {
        if ($this->task == "add") {
            if (!db_query("insert into software_info (
					software_HTML,
					visitor_count,
					parent_class,
					software_name,
					software_add,
					software_type,
					software_producer,
					software_language,
					software_size,
					software_environment,
					software_grade,
					software_time,
					software_text
					) values (\"%s\",0,%d,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",%d,\"%s\",%d,\"%s\",\"%s\")", array(
                        $this->software_HTML,
                        $this->class_id,
                        $this->software_name,
                        $this->software_add,
                        $this->software_type,
                        $this->software_producer,
                        $this->software_language,
                        $this->software_size,
                        $this->software_environment,
                        $this->software_grade,
                        $this->software_time,
                        $this->software_text
                    ))) {
                $this->error.="添加失败;";
            }
        }
    }

    protected function _check_save() {

        if (!$this->_check_fields())
            return FALSE;
        //Name has already got in _check_fields()
        return $this->_get_parentclass("id", 4, $this->software_id, $software_name);
    }

    public function save() {
        if ($this->task == "save") {
            if (!db_query("update software_info set
					software_HTML=\"%s\",
					software_name=\"%s\",
					software_add=\"%s\",
					software_type=\"%s\",
					software_producer=\"%s\",
					software_language=\"%s\",
					software_size=%d,
					software_environment=\"%s\",
					software_grade=%d,
					software_time=\"%s\",
					software_text=\"%s\" 
					where id=%d", array(
                        $this->software_HTML,
                        $this->software_name,
                        $this->software_add,
                        $this->software_type,
                        $this->software_producer,
                        $this->software_language,
                        $this->software_size,
                        $this->software_environment,
                        $this->software_grade,
                        $this->software_time,
                        $this->software_text,
                        $this->software_id
                    ))) {
                $this->error.="保存失败;";
            }
        }
    }

    protected function _check_delete() {
        return $this->_check_content("id", "software", $this->software_id, $this->software_name);
    }

    public function delete() {
        if ($this->task == "delete") {
            return $this->_delete("software", $this->software_id);
        }
        return FALSE;
    }

    protected function _check_recycle() {
        return $this->_check_content("id", "software", $this->software_id, $this->software_name);
    }

    public function recycle() {
        if ($this->task == "recycle") {
            return $this->_recycle("software", $this->software_id);
        }
        return FALSE;
    }

    //protected function _check_restore(){
    //	$check=$this->_check_content("id", "software", $this->software_id, $this->software_name);
    //	if(!$check) return FALSE;
    //	return $this->_check_parentclass("parent_class", 4);
    //}
    public function restore() {
        //	if($this->task=="restore"){
        $this->software_id = intval(strPost("contenttomove"));
        $this->class_id = intval(strPost("newclass"));
        return $this->_restore("software", $this->software_id, $this->class_id);
        //	}
        //	return FALSE;
    }

    protected function _check_move() {
        //get info of the new parent
        if ($this->_check_parentclass("newclass", 4)) {
            $this->new_parent_id = $this->class_id;
            $this->new_parent_name = $this->class_name;
            //get info of the old parent and self
            if ($this->_get_parentclass("contenttomove", 4, $this->software_id, $this->software_name)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function move() {
        if ($this->task == "move") {
            if ($this->_move(4, $this->software_id, $this->new_parent_id)) {
                $this->class_id = $this->new_parent_id;
                $this->class_name = $this->new_parent_name;
                return TRUE;
            }
        }
        return FALSE;
    }

}
