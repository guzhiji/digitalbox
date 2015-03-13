<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class ArticleCreatorBox extends BoxModel {

    function __construct($args = array()) {
        parent::__construct(__CLASS__, $args);
        $this->containerTplName = 'box3';
    }

    protected function LoadContent() {
        $catalogid = intval(strGet('catalog'));
        if (empty($catalogid)) {
            $this->Forward('MsgBox', array(
                'msg' => 'not found',
                'back' => 'back'
            ));
        } else {
            try {
                LoadIBC1Class('CatalogListReader', 'data.catalog');
                $reader = new CatalogListReader(DB3_SERVICE_CATALOG);
                $catalog = $reader->GetCatalog($catalogid);

                $this->SetField('Title', 'New Article');
                return $this->TransformTpl('editor', array(
                            'text_parent' => $catalog->Name,
                            'int_parent' => $catalogid,
                            'int_id' => '',
                            'text_title' => '',
                            'text_author' => '',
                            'content' => ''
                                )
                );
            } catch (Exception $ex) {
                $this->Forward('MsgBox', array(
                    'msg' => 'catalog not found',
                    'back' => 'back'
                ));
            }
        }

        return '';
    }

    public function After($page) {
        
    }

    public function Before($page) {
        $page->CheckPassport();
    }

}