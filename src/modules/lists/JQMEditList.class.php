<?php

LoadIBC1Class('ListModel', 'framework');

class JQMEditList extends ListModel {

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

}