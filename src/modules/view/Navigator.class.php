<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/Navigator.class.php
  ------------------------------------------------------------------
 */

require_once("modules/view/ListView.class.php");

/**
 * Description of Navigator
 *
 * @author guzhiji
 */
class Navigator extends ListView {

    protected $tpl2 = "";

    /**
     * constructor
     * @param string $tpl1name  default item template's name
     * @param string $tpl2name  optional, selected item template's name
     */
    function __construct($tpl1name, $tpl2name="") {
        parent::__construct($tpl1name);
        $this->tpl2 = ($tpl2name == "") ? "" : GetTemplate($tpl2name);
        $this->_prefix = __CLASS__;
    }

    /**
     *
     * @param string $name
     * @param string $page
     * @param string $tiptext
     * @param bool $selected
     * @param bool $eventdriven
     * @param string $target 
     */
    public function AddItem($name, $page, $tiptext="", $selected=FALSE, $eventdriven=FALSE, $target="") {

        //create the link
        $action = "";
        if ($eventdriven) {
            switch (strtolower($target)) {
                case "_blank":
                    $action = " onClick=\"window.open('{$page}','','')\"";
                    break;
                case "":
                case "_self":
                    $action = " onClick=\"window.location='{$page}'\"";
                    break;
                case "parent":
                    $action = " onClick=\"window.parent.location='{$page}'\"";
                    break;
                default:
                    $action = " onClick=\"window.parent.{$target}.location='{$page}'\"";
            }
        } else {
            $action = " href=\"{$page}\" target=\"{$target}\"";
        }

        //insert a new item
        $vars = array(
            "Name" => $name,
            "Action" => $action,
            "TipText" => $tiptext
        );

        if ($selected && $this->tpl2 != "") {
            $this->_items.=Tpl2HTML($this->tpl2, $vars, $this->_prefix);
        } else {
            parent::AddItem($vars);
        }
    }

}

?>
