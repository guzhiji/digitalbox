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
        $id = $this->GetBoxArgument('id');
        $c = $this->GetBoxArgument('content');

        LoadIBC1Class('UniqueKeyValueReader', 'data.keyvalue');
        $kvreader = new UniqueKeyValueReader(DB3_SERVICE_ARTICLE, $id);
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
    }

    public function After($page) {
        
    }

    public function Before($page) {
        
    }

}