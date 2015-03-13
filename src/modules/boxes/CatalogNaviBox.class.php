<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class CatalogNaviBox extends BoxModel {

    function __construct($args = array()) {
        parent::__construct(__CLASS__, $args);
        $this->containerTplName = 'box1';
    }

    protected function LoadContent() {

        LoadIBC1Class('CatalogListReader', 'data.catalog');
        $reader = new CatalogListReader(DB3_SERVICE_CATALOG);

        $id = intval(strGet('id'));

        //get parent catalog
        $parentid = 0;
        if (empty($id)) { //top level

            $id = 0; 
            $this->SetField('Title', 'Menu');

        } else {
            try {

                $c = $reader->GetCatalog($id);
                $parentid = $c->ParentID;
                $this->SetField('Title', $c->Name);

            } catch (Exception $ex) {
                $this->Hide();
                return '';
            }
        }

        //display children

        $reader->SetParentCatalog($id);
        $reader->LoadList();
        $reader->MoveFirst();

        require_once GetSysResPath('NaviButtonList.class.php', 'modules/lists');
        $list = new NaviButtonList();
        while ($c = $reader->GetEach()) {
            $list->AddItem(array(
                'text' => $c->Name,
                'url' => "?module=catalog&id={$c->ID}"
            ));
        }

        if ($id > 0) {
            $list->AddItem(array(
                'text' => '返回上级',
                'url' => $parentid == 0 ? '?module=' : "?module=catalog&id={$parentid}"
            ));
        }

        if ($list->ItemCount() == 0) {
            $this->Hide();
            return '';
        }
        
        return $list->GetHTML();
    }

    public function Before($page) {
        
    }

    public function After($page) {
        
    }

}
