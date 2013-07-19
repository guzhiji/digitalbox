<?php

class ContentListBox extends BoxModel {

    protected $reader;

    function __construct() {
        parent::__construct(__CLASS__);
        $this->containerTplName = 'box3';
    }

    public function LoadContent() {
        $id = intval(strGet('id'));
        if (empty($id)) {
            $this->Hide();
            return '';
        }
        $this->SetField('Title', 'Contents');
        LoadIBC1Class('ContentListReader', 'data.catalog');
        $reader = new ContentListReader(DB3_SERVICE_CATALOG);
        $reader->SetCatalog($id);
        $reader->LoadList();
        $reader->MoveFirst();
        $this->reader = $reader;

        $p = DB3_Passport();
        if ($p->IsOnline())
            return $this->RenderPHPTpl('admin', array(
                        'int_catalogid' => $id
                    ));
        else
            return $this->RenderPHPTpl('public', array(
                        'int_catalogid' => $id
                    ));
    }

    public function After($page) {
        
    }

    public function Before($page) {
        
    }

}