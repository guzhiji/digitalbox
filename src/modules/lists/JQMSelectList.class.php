<?php

LoadIBC1Class('ListModel', 'framework');

class JQMSelectList extends ListModel {

    function __construct() {
        parent::__construct('item', __CLASS__);
        $this->SetContainer('list');
    }

    protected function LoadData() {
        
    }

    public function After($page) {
        
    }

    public function Before($page) {
        
    }

    public function AddItems(array $items) {
        foreach ($items as $item) {
            $this->AddItem($item);
        }
    }

    public function AddItem(array $vars) {

        $selected = isset($vars['selected']) && $vars['selected'];

        if ($selected) {
            if (!isset($this->tpl2))
                $this->tpl2 = GetTemplate('item_selected', __CLASS__);
            if (!empty($this->tpl2))
                $this->_items.=$this->Tpl2HTML($this->tpl2, $vars);
            else
                parent::AddItem($vars);
        } else {
            parent::AddItem($vars);
        }
    }

}