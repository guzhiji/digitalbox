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

class Article_Admin extends Content_Admin {

    var $class_name, $class_id, $article_id, $article_name, $article_time, $article_author, $article_HTML, $article_text;

    function __construct(Passport &$passport) {
        $this->_passport = &$passport;
    }

    private function _check_fields() {

        $error = "";

        $this->article_name = strPost("article_name");
        $error.=CheckText("文章名称", 255, 1, $this->article_name, FALSE, FALSE);

        $this->article_author = strPost("article_author");
        $error.=CheckText("文章作者", 255, 1, $this->article_author, FALSE, FALSE);

        if (strPost("article_HTML") != "yes") {
            $this->article_HTML = "false";
            $ishtml = FALSE;
        } else {
            $this->article_HTML = "true";
            $ishtml = TRUE;
        }
        $this->article_text = strPost("article_text");
        $error.=CheckText("文章", 524288, 10, $this->article_text, $ishtml, TRUE);

        $this->article_time = date("Y-m-j H:i:s");

        if ($error != "") {
            $this->error.=$error;
            return FALSE;
        }
        return TRUE;
    }

    protected function _check_add() {

        if (!$this->_check_fields())
            return FALSE;
        return $this->_check_parentclass("parent_class", 1);
    }

    public function add() {
        if ($this->task == "add") {
            if (!db_query("insert into article_info (
					visitor_count,
					article_HTML,
					parent_class,
					article_time,
					article_name,
					article_author,
					article_text
					) values (0,\"%s\",%d,\"%s\",\"%s\",\"%s\",\"%s\")", array(
                        $this->article_HTML,
                        $this->class_id,
                        $this->article_time,
                        $this->article_name,
                        $this->article_author,
                        $this->article_text
                    ))) {
                $this->error.="添加失败;";
            }
        }
    }

    protected function _check_save() {

        if (!$this->_check_fields())
            return FALSE;
        //name has already got in _check_fields()
        //do not let old value write over new value
        return $this->_get_parentclass("id", 1, $this->article_id, $article_name);
    }

    public function save() {
        if ($this->task == "save") {
            if (!db_query("update article_info set
					article_HTML=\"%s\",
					article_name=\"%s\",
					article_author=\"%s\",
					article_time=\"%s\",
					article_text=\"%s\" 
					where id=%d", array(
                        $this->article_HTML,
                        $this->article_name,
                        $this->article_author,
                        $this->article_time,
                        $this->article_text,
                        $this->article_id
                    ))) {
                $this->error.="保存失败;";
            }
        }
    }

    protected function _check_delete() {

        return $this->_check_content("id", "article", $this->article_id, $this->article_text);
    }

    public function delete() {

        if ($this->task == "delete") {
            return $this->_delete("article", $this->article_id);
        }
        return FALSE;
    }

    protected function _check_recycle() {

        return $this->_check_content("id", "article", $this->article_id, $this->article_text);
    }

    public function recycle() {

        if ($this->task == "recycle") {
            return $this->_recycle("article", $this->article_id);
        }
        return FALSE;
    }

    //protected function _check_restore(){
    //	$check = $this->_check_content("id", "article", $this->article_id, $this->article_text);
    //	if(!$check) return FALSE;
    //	return $this->_check_parentclass("parent_class", 1);
    //}
    public function restore() {

        //if($this->task=="restore"){
        $this->article_id = intval(strPost("contenttomove"));
        $this->class_id = intval(strPost("newclass"));
        return $this->_restore("article", $this->article_id, $this->class_id);
        //}
        //return FALSE;
    }

    protected function _check_move() {
        //get info of the new parent
        if ($this->_check_parentclass("newclass", 1)) {
            $this->new_parent_id = $this->class_id;
            $this->new_parent_name = $this->class_name;
            //get info of the old parent and self
            if ($this->_get_parentclass("contenttomove", 1, $this->aritcle_id, $this->aritcle_name)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function move() {
        if ($this->task == "move") {
            if ($this->_move(1, $this->aritcle_id, $this->new_parent_id)) {
                $this->class_id = $this->new_parent_id;
                $this->class_name = $this->new_parent_name;
                return TRUE;
            }
        }
        return FALSE;
    }

}
