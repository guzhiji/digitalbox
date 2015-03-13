<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/GridView.class.php
  ------------------------------------------------------------------
 */

/**
 * Description of GridView
 *
 * @author guzhiji
 */
class GridView {

    protected $_coln;
    protected $_rown;
    protected $_table;
    protected $_row;
    protected $_itemtpl;
    protected $_rowtpl;
    protected $_tabletpl;
    protected $_itemcount;
    protected $_itemempty;
    protected $_itemrest;
    protected $_prefix;

    function __construct($itemTplName, $rowTplName, $tableTplName, $coln) {
        $this->_coln = intval($coln);
        if ($this->_coln < 1)
            $this->_coln = 1;
        $this->_table = "";
        $this->_row = "";
        $this->_itemempty = "";
        $this->_itemrest = "";
        $this->_itemtpl = GetTemplate($itemTplName);
        $this->_rowtpl = GetTemplate($rowTplName);
        $this->_tabletpl = GetTemplate($tableTplName);
        $this->_itemcount = 0;
        $this->_prefix = __CLASS__;
    }

    public function SetRestItem($tplname, $vars) {
        $this->_itemrest = TransformTpl($tplname, $vars, $this->_prefix);
    }

    public function SetEmptyItem($tplname, $vars) {
        $this->_itemempty = TransformTpl($tplname, $vars, $this->_prefix);
    }

    private function AddRow($row) {
        $this->_table .= Tpl2HTML($this->_rowtpl, array("RowContent" => $row), $this->_prefix);
        $this->_rown++;
    }

    public function AddItem($vars) {
        $this->_row.=Tpl2HTML($this->_itemtpl, $vars, $this->_prefix);
        $this->_itemcount++;
        $m = $this->_itemcount % $this->_coln;
        if ($m == 0) {
            $this->AddRow($this->_row);
            $this->_row = "";
        }
    }

    public function ItemCount() {
        return $this->_itemcount;
    }

    public function ColCount() {
        return $this->_coln;
    }

    public function RowCount() {
        return $this->_row == "" ? $this->_rown : $this->_rown + 1;
    }

    public function Size() {
        return $this->RowCount() * $this->_coln;
    }

    public function GetHTML() {
        if ($this->_itemcount == 0) {
            $this->AddRow($this->_itemempty);
        } else {
            $m = $this->_itemcount % $this->_coln;
            if ($m > 0) {
                while ($m < $this->_coln) {
                    $this->_row .= $this->_itemrest;
                    $m++;
                }
                $this->AddRow($this->_row);
                $this->_row = "";
            }
        }
        return Tpl2HTML($this->_tabletpl, array("TableContent" => $this->_table), $this->_prefix);
    }

}

?>
