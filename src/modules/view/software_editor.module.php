<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/software_editor.module.php
  ------------------------------------------------------------------
 */

class Software_Editor {

    var $_types = array(
        "操作系统",
        "服务软件",
        "安全保护",
        "数据管理",
        "处理制作",
        "程序开发",
        "播放浏览",
        "压缩传输",
        "资源软件",
        "插件补丁",
        "游戏娱乐",
        "另类工具"
    );
    var $_grades = array(
        "★",
        "★★",
        "★★★",
        "★★★★",
        "★★★★★"
    );
    var $_languages = array(
        "中文（简体）",
        "中文（繁体）",
        "英文"
    );
    var $_sizeunits = array("Byte",
        "KB",
        "MB",
        "GB"
    );
    var $_func;
    var $_classid;
    var $_classname;
    var $_id;
    var $_name;
    var $_addr;
    var $_text;
    var $_type;
    var $_producer;
    var $_grade;
    var $_lang;
    var $_size;
    var $_unit;
    var $_env;

    function __construct() {
        $this->_func = "add";
        $this->_classid = 0;
        $this->_classname = "";
        $this->_id = ""; //initial must be empty
        $this->_name = "";
        $this->_addr = dbUploadPath . "/";
        $this->_text = "";
        $this->_type = "另类工具";
        $this->_producer = "";
        $this->_grade = 0;
        $this->_lang = "中文（简体）";
        $this->_size = 0;
        $this->_unit = "Byte";
        $this->_env = "Windows 9X/2000/XP";
    }

    private function getOptions(&$options, $option) {
        $html = "";
        foreach ($options as $o) {
            $html.="<option value=\"{$o}\"";
            if ($o == $option) {
                $html.=" selected=\"selected\"";
            }
            $html.=">{$o}</option>";
        }
        return $html;
    }

    private function getTypeOptions() {
        return $this->getOptions($this->_types, $this->_type);
    }

    private function getGradeOptions() {
        $html = "";
        for ($i = 1; $i <= count($this->_grades); $i++) {
            $html.="<option value=\"{$i}\"";
            if ($i == $this->_grade) {
                $html.=" selected=\"selected\"";
            }
            $html.=">{$this->_grades[$i - 1]}</option>";
        }
        return $html;
    }

    private function getLangOptions() {
        return $this->getOptions($this->_languages, $this->_lang);
    }

    private function getSizeUnitOptions() {
        return $this->getOptions($this->_sizeunits, $this->_unit);
    }

    public function setFunction($f) {
        $this->_func = $f;
    }

    public function setClass($id, $name) {
        $this->_classid = intval($id);
        $this->_classname = $name;
    }

    public function setID($id) {
        $this->_id = intval($id);
    }

    public function setName($name) {
        $this->_name = $name;
    }

    public function setAddr($addr) {
        $this->_addr = $addr;
    }

    public function setText($text) {
        $this->_text = $text;
    }

    public function setType($type) {
        $this->_type = $type;
    }

    public function setProducer($producer) {
        $this->_producer = $producer;
    }

    public function setGrade($grade) {
        $this->_grade = intval($grade);
    }

    public function setLanguage($lang) {
        $this->_lang = $lang;
    }

    public function setSize($size) {
        $s = explode(" ", GetSizeWithUnit($size));
        $this->_size = $s[0];
        $this->_unit = $s[1];
    }

    public function setEnv($env) {
        $this->_env = $env;
    }

    public function getHTML() {

        $html = GetTemplate("software_editor", array(
            "Content_Function" => $this->_func,
            "Content_Class" => $this->_classid,
            "Content_ClassName" => $this->_classname,
            "Content_ID" => $this->_id,
            "Content_Name" => TextForInputBox($this->_name),
            "Content_Addr" => $this->_addr,
            "Content_Text" => HTMLForTextArea($this->_text),
            "Software_Type" => TextForInputBox($this->_type),
            "Software_TypeOptions" => $this->getTypeOptions(),
            "Software_Producer" => TextForInputBox($this->_producer),
            "Software_GradeOptions" => $this->getGradeOptions(),
            "Software_Language" => TextForInputBox($this->_lang),
            "Software_LangOptions" => $this->getLangOptions(),
            "Software_Size" => $this->_size,
            "Software_UnitOptions" => $this->getSizeUnitOptions(),
            "Software_Env" => TextForInputBox($this->_env)
                ));

        return $html;
    }

}

?>
