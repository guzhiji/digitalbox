<?php

/**
 * Description of GridView
 *
 * @author guzhiji
 */
class GridView1 {

    private $_coln;
    private $_padding;
    private $_spacing;
    private $_border;
    private $_table;
    private $_itemtpl;
    private $_itemcount;
    private $_itemempty;
    private $_itemrest;

    function __construct($itemTplName, $coln, $padding=0, $spacing=0, $border=0) {
        $this->_coln = intval($coln);
        if ($this->_coln < 1)
            $this->_coln = 1;
        $this->_padding = intval($padding);
        $this->_spacing = intval($spacing);
        $this->_border = intval($border);
        $this->_table = "";
        $this->_itemempty = "";
        $this->_itemrest = "";
        $this->_itemtpl = GetTemplate($itemTplName);
        $this->_itemcount = 0;
    }

    public function SetRestItem($tplname, $vars) {
        $this->_itemrest = TransformTpl($tplname, $vars);
    }

    public function SetEmptyItem($tplname, $vars) {
        $this->_itemempty = TransformTpl($tplname, $vars);
    }

    public function AddItem($vars) {
        $m = $this->_itemcount % $this->_coln;
        if ($m == 0)
            $this->_table .= "<tr>";
        $this->_table.="<td>" . Tpl2HTML($this->_itemtpl, $vars) . "</td>";
        if ($m == $this->_coln - 1)
            $this->_table .= "</tr>";
        $this->_itemcount++;
    }

    public function GetHTML() {
        if ($this->_itemcount == 0) {
            $this->_table = "<tr><td>{$this->_itemempty}</td></tr>";
        } else {
            $m = $this->_itemcount % $this->_coln;
            if ($m < $this->_coln - 1) {
                while ($m < $this->_coln - 1) {
                    $this->_table .= "<td>{$this->_itemrest}</td>";
                    $m++;
                }
                $this->_table .= "</tr>";
            }
        }
        return "<table cellpadding=\"{$this->_padding}\" cellspacing=\"{$this->_spacing}\" border=\"{$this->_border}\">{$this->_table}</table>";
    }

}

?>
