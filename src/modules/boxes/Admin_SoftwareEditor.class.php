<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_SoftwareEditor extends Box {

    private $types;
    private $grades = array(
        "★",
        "★★",
        "★★★",
        "★★★★",
        "★★★★★"
    );
    private $languages;
    private $sizeunits = array("Byte",
        "KB",
        "MB",
        "GB"
    );
    private $func;
    private $classid;
    private $classname;
    private $id;
    private $name;
    private $addr;
    private $text;
    private $type;
    private $producer;
    private $grade;
    private $lang;
    private $size;
    private $unit;
    private $env;

    function __construct() {
        parent::__construct("Left", "box3");
        $this->types = GetLangData("softwaretypes");
        $this->languages = GetLangData("softwarelang");
        $this->func = "add";
        $this->classid = 0;
        $this->classname = "";
        $this->id = ""; //initial must be empty
        $this->name = "";
        $this->addr = dbUploadPath . "/";
        $this->text = "";
        $this->type = $this->types[0];
        $this->producer = "";
        $this->grade = 0;
        $this->lang = $this->languages[0];
        $this->size = 0;
        $this->unit = "Byte";
        $this->env = "Windows 9X/2000/XP/7/8";
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
        return $this->getOptions($this->types, $this->type);
    }

    private function getGradeOptions() {
        $html = "";
        for ($i = 1; $i <= count($this->grades); $i++) {
            $html.="<option value=\"{$i}\"";
            if ($i == $this->grade) {
                $html.=" selected=\"selected\"";
            }
            $html.=">{$this->grades[$i - 1]}</option>";
        }
        return $html;
    }

    private function getLangOptions() {
        return $this->getOptions($this->languages, $this->lang);
    }

    private function getSizeUnitOptions() {
        return $this->getOptions($this->sizeunits, $this->unit);
    }

    public function setFunction($f) {
        $this->func = $f;
    }

    public function setClass($id, $name) {
        $this->classid = intval($id);
        $this->classname = $name;
    }

    public function setID($id) {
        $this->id = intval($id);
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setAddr($addr) {
        $this->addr = $addr;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setProducer($producer) {
        $this->producer = $producer;
    }

    public function setGrade($grade) {
        $this->grade = intval($grade);
    }

    public function setLanguage($lang) {
        $this->lang = $lang;
    }

    public function setSize($size) {
        $s = explode(" ", GetSizeWithUnit($size));
        $this->size = $s[0];
        $this->unit = $s[1];
    }

    public function setEnv($env) {
        $this->env = $env;
    }

    private function setHTML() {
        require("modules/filters.lib.php");
        $this->_content = TransformTpl("software_editor", array(
            "Content_Function" => $this->func,
            "Content_Class" => $this->classid,
            "Content_ClassName" => $this->classname,
            "Content_ID" => $this->id,
            "Content_Name" => TextForInputBox($this->name),
            "Content_Addr" => $this->addr,
            "Content_Text" => HTMLForTextArea($this->text),
            "Software_Type" => TextForInputBox($this->type),
            "Software_TypeOptions" => $this->getTypeOptions(),
            "Software_Producer" => TextForInputBox($this->producer),
            "Software_GradeOptions" => $this->getGradeOptions(),
            "Software_Language" => TextForInputBox($this->lang),
            "Software_LangOptions" => $this->getLangOptions(),
            "Software_Size" => $this->size,
            "Software_UnitOptions" => $this->getSizeUnitOptions(),
            "Software_Env" => TextForInputBox($this->env)
                ), __CLASS__);
    }

    public function DataBind() {

        $this->SetAlign("center", "top");
        $this->SetPadding(10);

        global $_contentID;
        global $_classID;
        global $_className;
        global $_channelType;
        $title = "";

        $typename = GetTypeName($_channelType, 0);
        if (isset($_contentID) && !empty($_contentID)) {
            //MODIFY CONTENT
            $title = GetLangData("edit") . $typename;

            //prepare sql
            require_once("modules/data/sql_content.module.php");
            $sql = new SQL_Content();
            $sql->SetMode($_channelType);
            $sql->SetContentID($_contentID);

            $sql->AddField("software_producer");
            $sql->AddField("software_type");
            $sql->AddField("software_language");
            $sql->AddField("software_size");
            $sql->AddField("software_environment");
            $sql->AddField("software_grade");
            $sql->AddField("software_add");
            $sql->AddField("software_text");

            //run sql
            $rs = db_query($sql->GetSelect());
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    //generate html
                    $this->setFunction("save");
                    $this->setClass($list[0]["class_id"], $list[0]["class_name"]);
                    $this->setID($list[0]["id"]);
                    $this->setName($list[0]["content_name"]);
                    $this->setAddr($list[0]["software_add"]);
                    $this->setText($list[0]["software_text"]);
                    $this->setType($list[0]["software_type"]);
                    $this->setProducer($list[0]["software_producer"]);
                    $this->setGrade($list[0]["software_grade"]);
                    $this->setLanguage($list[0]["software_language"]);
                    $this->setSize($list[0]["software_size"]);
                    $this->setEnv($list[0]["software_environment"]);
                    $this->setHTML();
                } else {
                    $this->_status = 1;
                }
                db_free($rs);
            } else {
                $this->_status = 1;
            }
        } else if (isset($_classID) && isset($_className)) {
            //CREATE NEW CONTENT
            $title = GetLangData("add") . $typename;

            //generate html
            $this->setClass($_classID, $_className);
            $this->setHTML();
        } else {
            $this->_status = 1;
        }

        if ($this->_status == 1) {
            $this->SetTitle(GetLangData("error"));
            $this->_error = "not found"; //TODO error message
            $this->_backpage = "back";
        } else {
            $this->SetTitle($title);
        }
    }

}
