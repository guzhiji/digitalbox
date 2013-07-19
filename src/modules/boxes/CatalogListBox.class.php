<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class CatalogListBox extends BoxModel {

    protected $reader;

    function __construct() {
        parent::__construct(__CLASS__);
        $this->containerTplName = 'box3';
    }

    public function LoadContent() {
        $this->SetField('Title', 'Catalogs');
        $id = intval(strGet('id'));
        LoadIBC1Class('CatalogListReader', 'data.catalog');
        $reader = new CatalogListReader(DB3_SERVICE_CATALOG);

        //get parent catalog
        $parentid = 0;
        if (empty($id)) {
            $id = 0; //top level
        } else {
            try {
                $c = $reader->GetCatalog($id);
                $parentid = $c->ParentID;
            } catch (Exception $ex) {
                $this->Forward('MsgBox', array('msg' => 'This catalog is not found.'));
                return '';
            }
        }

        //display children
        $reader->SetParentCatalog($id);
        $reader->LoadList();
        $reader->MoveFirst();
        $this->reader = $reader;

        $p = DB3_Passport();
        if ($p->IsOnline())
            return $this->RenderPHPTpl('admin', array(
                        'int_id' => $id,
                        'int_parent' => $parentid
                    ));
        else
            return $this->RenderPHPTpl('public', array(
                        'int_id' => $id,
                        'int_parent' => $parentid
                    ));
    }

    public function After($page) {
        
    }

    public function Before($page) {
        LoadIBC1Class('CatalogServiceManager', 'data.catalog');
        $m = new CatalogServiceManager(DB3_SERVICE_CATALOG);
        if (!$m->IsInstalled())
            $m->Install();
        LoadIBC1Class('KeyValueServiceManager', 'data.keyvalue');
        $m = new KeyValueServiceManager(DB3_SERVICE_ARTICLE);
        if (!$m->IsInstalled())
            $m->Install();
        $m = new KeyValueServiceManager(DB3_SERVICE_PHOTO);
        if (!$m->IsInstalled())
            $m->Install();
        $m = new KeyValueServiceManager(DB3_SERVICE_COMMENT);
        if (!$m->IsInstalled())
            $m->Install();
    }

}