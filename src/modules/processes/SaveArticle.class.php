<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require 'SaveContent.class.php';

class SaveArticle extends SaveContent {

    function __construct() {
        parent::__construct('article');
    }

    protected function GetInputMeta() {
        return array(
            array(// alias for required common fields
                'id' => 'id',
                'pid' => 'parent_catalog',
                'name' => 'article_name',
                'author' => 'article_author'
            ),
            array(
                'id' => array('get|post', 0, array(
                        'filter' => 'intval'
                    )
                ),
                'parent_catalog' => array('post', 0, array(
                        'filter' => 'intval'
                    )
                ),
                'article_name' => array('post', '', array(
                        'setter' => 'SetName'
                    )
                ),
                'article_author' => array('post', '', array(
                        'setter' => 'SetAuthor'
                    )
                ),
                'article_text' => array('post', '')
            )
        );
    }

    protected function CreateAttributes($id, $vars) {

        LoadIBC1Class('UniqueKeyValueEditor', 'data.keyvalue');
        $kveditor = new UniqueKeyValueEditor(DB3_SERVICE_ARTICLE, $id);
        $kveditor->SetValue('text', $vars['article_text']);
        $kveditor->Save();
    }

    protected function ModifyAttributes($id, $vars) {

        LoadIBC1Class('UniqueKeyValueEditor', 'data.keyvalue');
        $kveditor = new UniqueKeyValueEditor(DB3_SERVICE_ARTICLE, $id);
        $kveditor->SetValue('text', $vars['article_text']);
        $kveditor->Save();
    }

}
