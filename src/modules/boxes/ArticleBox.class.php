<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class ArticleBox extends BoxModel {

    function __construct($args = array()) {
        parent::__construct(__CLASS__, $args);
        $this->containerTplName = 'box3';
    }

    protected function LoadContent() {
        $id = intval(strGet('id'));
        if (empty($id)) {
            $this->Forward('MsgBox', array(
                'msg' => 'not found',
                'back' => 'back'
            ));
            return '';
        } else {
            LoadIBC1Class('ContentListReader', 'data.catalog');
            try {
                $reader = new ContentListReader(SERVICE_CATALOG);
                $c = $reader->GetContent($id);

                LoadIBC1Class('UniqueKeyValueReader', 'data.keyvalue');
                $kvreader = new UniqueKeyValueReader(SERVICE_ARTICLE, $id);
                $text = $kvreader->GetValue('text');

                $this->SetField('Title', $c->Name);
                return $this->TransformTpl('article', array(
                            'text_parent' => $c->CatalogID,
                            'int_parent' => $c->CatalogID,
                            'int_id' => $c->ID,
                            'text_title' => $c->Name,
                            'text_author' => $c->Author,
                            'content' => $text
                                )
                );
            } catch (Exception $ex) {
                $this->Forward('MsgBox', array(
                    'msg' => 'The article is not found.',
                    'back' => 'back'
                ));
                return '';
            }
        }
    }

    public function After($page) {
        
    }

    public function Before($page) {
        
    }

}