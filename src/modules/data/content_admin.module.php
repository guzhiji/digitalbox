<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

abstract class Content_Admin {

    var $_passport, $task, $error;

    /**
     * get information of parent class
     * @param string $idfieldname
     * @param int $type
     * @param int $contentid
     * variable to save content id
     * @param string $contentname
     * variable to save content name
     * @return bool
     */
    protected function _get_parentclass($idfieldname, $type, &$contentid, &$contentname) {

        $e = FALSE;
        $typename = GetTypeName($type, 1);
        $id = trim(strPost($idfieldname));
        $rs = db_query("select class_info.class_name,{$typename}_info.parent_class,{$typename}_info.{$typename}_name from channel_info,class_info,{$typename}_info where channel_info.channel_type=%d and channel_info.id=class_info.parent_channel and class_info.id={$typename}_info.parent_class and {$typename}_info.id=%d", array($type, $id));
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0])) {
                $this->class_name = $list[0]["class_name"];
                $this->class_id = $list[0]["parent_class"];
                $contentname = $list[0]["{$typename}_name"];
                $contentid = intval($id);
            } else {
                $e = TRUE;
            }
            db_free($rs);
        } else {
            $e = TRUE;
        }
        if ($e) {
            $this->error .= "找不到内容对象;";
            return FALSE;
        }
        return TRUE;
    }

    /**
     * check parent class id
     * @param string $parentfieldname
     * @param int $type
     * @return bool
     */
    protected function _check_parentclass($parentfieldname, $type = NULL) {
        $parent_class = trim(strPost($parentfieldname));
        $e = FALSE;
        if ($parent_class != "") {
            if ($type == NULL) {
                $rs = db_query("select id,class_name from class_info where id=%d", array($parent_class));
            } else {
                $rs = db_query("select class_info.class_name from class_info,channel_info where channel_info.id=class_info.parent_channel and channel_info.channel_type=%d and class_info.id=%d", array($type, $parent_class));
            }
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    $this->class_name = $list[0]["class_name"];
                    $this->class_id = intval($parent_class);
                } else {
                    $e = TRUE;
                }
                db_free($rs);
            } else {
                $e = TRUE;
            }
        } else {
            $e = TRUE;
        }
        if ($e) {
            $this->error .= "找不到栏目或者栏目类型错误;";
            return FALSE;
        }
        return TRUE;
    }

    /**
     * check content id
     * @param string $idfieldname
     * @param string $contenttype
     * @param int $contentid
     * variable to save content id
     * @param string $contentname
     * variable to save content name
     * @return bool
     */
    protected function _check_content($idfieldname, $contenttype, &$contentid, &$contentname) {
        $e = FALSE;
        $id = strPost($idfieldname);
        if ($id != "") {
            $rs = db_query("select * from {$contenttype}_info where id=%d", array($id));
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    $this->class_id = $list[0]["parent_class"];
                    $contentid = $list[0]["id"];
                    $contentname = $list[0]["{$contenttype}_name"];
                } else {
                    $e = TRUE;
                }
            } else {
                $e = TRUE;
            }
            db_free($rs);
        } else {
            $e = TRUE;
        }
        if ($e) {
            $this->error .= "找不到内容对象;";
            return FALSE;
        }
        return TRUE;
    }

    /**
     * delete a content
     * @param string $contenttype
     * @param int $id
     * @return bool
     */
    protected function _delete($contenttype, $id) {
        $e = FALSE;
        if ($this->task == "delete") {
            if (!db_query("delete from {$contenttype}_info where id=%d", array($id))) {
                $e = TRUE;
            }
        } else {
            $e = TRUE;
        }
        if ($e) {
            $this->error.="删除失败;";
            return FALSE;
        }
        return TRUE;
    }

    /**
     * recycle a content
     * @param string $contenttype
     * @param int $id
     * @return bool
     */
    protected function _recycle($contenttype, $id) {
        $e = FALSE;
        if ($this->task == "recycle") {
            if (!db_query("update {$contenttype}_info set parent_class=0 where id=%d", array($id))) {
                $e = TRUE;
            }
        } else {
            $e = TRUE;
        }
        if ($e) {
            $this->error.="回收失败;";
            return FALSE;
        }
        return TRUE;
    }

    /**
     * restore a recycled content to a class
     * @param string $contenttype
     * @param int $id
     * @param int $classid
     * @return bool
     */
    protected function _restore($contenttype, $id, $classid) {
        $e = FALSE;
        //validate the type
        $type = GetTypeNumber($contenttype);
        if ($type > 0)
            $e = TRUE;
        if ($e) {
            //validate the new class
            $rs = db_query("SELECT class_info.id,class_info.class_name FROM channel_info,class_info WHERE class_info.parent_channel=channel_info.id AND channel_info.channel_type=%d AND class_info.id=%d", array($type, $classid));
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    $this->new_parent_id = $list[0]["id"];
                    $this->new_parent_name = $list[0]["class_name"];
                } else {
                    $e = FALSE;
                }
                db_free($rs);
            } else {
                $e = FALSE;
            }
        }
        if ($e) {
            //validate the content
            $rs = db_query("SELECT \"true\" FROM {$contenttype}_info WHERE parent_class<1 AND id=%d", array($id));
            if ($rs) {
                $list = db_result($rs);
                if (!isset($list[0])) {
                    $e = FALSE;
                }
                db_free($rs);
            } else {
                $e = FALSE;
            }
        }
        if ($e) {
            //restore
            if (!db_query("UPDATE {$contenttype}_info SET parent_class=%d WHERE id=%d", array($classid, $id))) {
                $e = FALSE;
            }
        }
        if (!$e) {
            $this->error.="还原失败;";
            return FALSE;
        }
        return TRUE;
    }

    /**
     * move a content to another class
     * @param int $type
     * @param int $id
     * @param int $classid
     * @return bool
     */
    protected function _move($type, $id, $classid) {
        $e = FALSE;
        if ($this->task == "move") {
            $contenttype = GetTypeName($type, 1);
            if (!db_query("update {$contenttype}_info set parent_class=%d where id=%d", array($classid, $id))) {
                $e = TRUE;
            }
        } else {
            $e = TRUE;
        }
        if ($e) {
            $this->error.="移动失败;";
            return FALSE;
        }
        return TRUE;
    }

    /**
     * fetch and validate parameters for indicated function
     * @param string $function
     */
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
            case "save":
                $this->task = ($this->_check_save()) ? "save" : "";
                break;
            case "move":
                $this->task = ($this->_check_move()) ? "move" : "";
                break;
            case "recycle":
                $this->task = ($this->_check_recycle()) ? "recycle" : "";
                break;
            //case "restore":
            //	$this->task=($this->_check_restore())?"restore":"";
            //	break;
            case "delete":
                $this->task = ($this->_check_delete()) ? "delete" : "";
                break;
            default:
                $this->task = "";
                $this->error .= "任务调度错误;";
        }
        return ($this->error == "");
    }

    protected abstract function _check_add();

    protected abstract function _check_save();

    protected abstract function _check_move();

    protected abstract function _check_recycle();

    //protected abstract function _check_restore();
    protected abstract function _check_delete();

    abstract function add();

    abstract function save();

    abstract function move();

    abstract function delete();

    abstract function recycle();

    abstract function restore();
}