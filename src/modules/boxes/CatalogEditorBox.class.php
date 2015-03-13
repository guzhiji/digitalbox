<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class CatalogEditorBox extends BoxModel {

    function __construct($args = array()) {
        parent::__construct(__CLASS__, $args);
        $this->containerTplName = 'box3';
    }

    protected function LoadContent() {
        $id = strGet('id');
        if (empty($id)) {
            $this->SetField('Title', 'New Catalog');
            return $this->TransformTpl('editor', array(
                        'url' => queryString(array(
                            'module' => $this->module,
                            'function' => 'save'
                                )
                        ),
                        'int_parent' => strGet('parent'),
                        'text_value' => ''
                            )
            );
        }
        LoadIBC1Class('CatalogListReader', 'data.catalog');
        $reader = new CatalogListReader(DB3_SERVICE_CATALOG);
        try {
            $c = $reader->GetCatalog($id);

            $this->SetField('Title', 'Edit Catalog');
            return $this->TransformTpl('editor', array(
                        'url' => queryString(array(
                            'module' => $this->module,
                            'function' => 'save',
                            'id' => $c->ID
                                )
                        ),
                        'int_parent' => $c->ParentID,
                        'text_value' => $c->Name
                            )
            );
        } catch (Exception $ex) {
            $this->Forward('MsgBox', array(
                'msg' => 'This catalog is not found.',
                'back' => 'back'
            ));
            return '';
        }
    }

    public function After($page) {
        
    }

    public function Before($page) {
        $page->CheckPassport();
    }

}