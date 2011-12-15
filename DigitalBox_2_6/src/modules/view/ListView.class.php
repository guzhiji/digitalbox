<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/ListView.class.php
  ------------------------------------------------------------------
 */

/**
 * Description of ListView
 *
 * @author guzhiji
 */
class ListView {

    protected $_container;
    protected $_containervars;
    protected $_items;
    protected $_itemtpl;
    protected $_emptytplname;
    protected $_prefix;

    function __construct($itemTplName, $emptyTplName="") {

        $this->_container = "";
        $this->_containervars = array();
        $this->_items = "";
        $this->_itemtpl = GetTemplate($itemTplName);
        $this->_emptytplname = $emptyTplName;
        $this->_prefix = __CLASS__;
    }

    public function SetContainer($tplname, $vars=array()) {
        $this->_container = GetTemplate($tplname);
        $this->_containervars = $vars;
    }

    public function AddItem($vars) {
        $this->_items .= Tpl2HTML($this->_itemtpl, $vars, $this->_prefix);
    }

    public function Clear() {
        $this->_items = "";
    }

    public function IsEmpty() {
        return $this->_items == "";
    }

    public function GetHTML() {
        if ($this->_items == "" && $this->_emptytplname != "")
            $this->_items = GetTemplate($this->_emptytplname);
        if ($this->_container != "") {
            $this->_containervars["ListItems"] = $this->_items;
            return Tpl2HTML($this->_container, $this->_containervars, $this->_prefix);
        } else {
            return $this->_items;
        }
    }

}

?>
