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
            array( // alias for required common fields
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
                'article_name' => array('post', ''),
                'article_author' => array('post', ''),
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

//---------------------
// class SaveArticle extends ProcessModel {

//     public function Auth($page) {
//         $page->CheckPassport();
//         return TRUE;
//     }

//     public function Process() {

//         $id = intval(readParam('get|post', 'id'));
//         $username = ''; // $up->GetUID();
//         $pid = intval(strPost('parent_catalog'));
//         $name = strPost('article_name');
//         $author = strPost('article_author');
//         $text = strPost('article_text');
//         $output = NULL;

//         LoadIBC1Class('ContentItemEditor', 'data.catalog');
//         $editor = new ContentItemEditor(DB3_SERVICE_CATALOG);

//         if (empty($id)) { // create
//             $editor->Create();
//             // set attributes
//             $editor->SetUID($username);
//             $editor->SetName($name);
//             $editor->SetAuthor($author);
//             // save
//             try {
//                 $editor->SetModule('article');
//                 $editor->Save($pid);

//                 LoadIBC1Class('UniqueKeyValueEditor', 'data.keyvalue');
//                 $kveditor = new UniqueKeyValueEditor(DB3_SERVICE_ARTICLE, $editor->GetID());
//                 $kveditor->SetValue('text', $text);
//                 $kveditor->Save();

//                 // success
//                 $output = $this->OutputBox('MsgBox', array(
//                     'msg' => 'succeed',
//                     'back' => DB3_URL('admin', 'catalog', '', array(
//                         'id' => $pid
//                     ))
//                         )
//                 );
//             } catch (Exception $ex) {
//                 // failure
//                 $output = $this->OutputBox('MsgBox', array(
//                     'msg' => 'fail: ' . $ex->getMessage(),
//                     'back' => 'back'
//                         )
//                 );
//             }
//         } else { // modify
//             $editor->Open($id);
//             // set changed attributes
//             if (!empty($name))
//                 $editor->SetName($name);
//             if (!empty($author))
//                 $editor->SetAuthor($author);
//             // save
//             try {
//                 $editor->Save();

//                 LoadIBC1Class('UniqueKeyValueEditor', 'data.keyvalue');
//                 $kveditor = new UniqueKeyValueEditor(DB3_SERVICE_ARTICLE, $editor->GetID());
//                 $kveditor->SetValue('text', $text);
//                 $kveditor->Save();

//                 // success
//                 $output = $this->OutputBox('MsgBox', array(
//                     'msg' => 'succeed',
//                     'back' => DB3_URL('admin', 'catalog', '', array(
//                         'id' => $pid
//                     ))
//                         )
//                 );
//             } catch (Exception $ex) {
//                 // failure
//                 $output = $this->OutputBox('MsgBox', array(
//                     'msg' => 'fail:' . $editor->GetID() . ',' . $ex->getMessage(),
//                     'back' => 'back'
//                         )
//                 );
//             }
//         }
//         return $output;
//     }

// }