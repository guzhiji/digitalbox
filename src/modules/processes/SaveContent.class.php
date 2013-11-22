<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

abstract class SaveContent extends ProcessModel {

    private $contentModule;

    function __construct($module) {
        $this->contentModule = $module;
    }

    abstract protected function GetInputMeta();

    abstract protected function CreateAttributes($id, $vars);

    abstract protected function ModifyAttributes($id, $vars);

    public function Auth($page) {
        $page->CheckPassport();
        return TRUE;
    }

    public function Process() {

        // $id = intval(readParam('get|post', 'id'));
        // $pid = intval(strPost('parent_catalog'));
        // $name = strPost('article_name');
        // $author = strPost('article_author');
        // $text = strPost('article_text');

        $username = ''; // $up->GetUID();
        $meta = $this->GetInputMeta();
        $rfields = $meta[0];
        $vars = readAllParams($meta[1]);
        $output = NULL;

        LoadIBC1Class('ContentItemEditor', 'data.catalog');
        $editor = new ContentItemEditor(DB3_SERVICE_CATALOG);

        if (empty($vars[$rfields['id']])) { // create

            $editor->Create();
            // set attributes
            $editor->SetUID($username); //TODO put username into meta
            $editor->SetName($vars[$rfields['name']]);
            $editor->SetAuthor($vars[$rfields['author']]);
            // save
            try {
                $editor->SetModule($this->contentModule);
                $editor->Save($vars[$rfields['pid']]);

                $this->CreateAttributes($editor->GetID(), $vars);

                // success
                $output = $this->OutputBox('MsgBox', array(
                    'msg' => 'succeed',
                    'back' => DB3_URL('admin', 'catalog', '', array(
                        'id' => $vars[$rfields['pid']]
                        )
                    ))
                );
            } catch (Exception $ex) {
                // failure
                $output = $this->OutputBox('MsgBox', array(
                    'msg' => 'fail: ' . $ex->getMessage(),
                    'back' => 'back'
                    )
                );
            }

        } else { // modify

            $editor->Open($vars[$rfields['id']]);

            // set changed attributes
            if (isset($vars[$rfields['name']]) && !empty($vars[$rfields['name']]))
                $editor->SetName($vars[$rfields['name']]);
            if (isset($vars[$rfields['author']]) && !empty($vars[$rfields['author']]))
                $editor->SetAuthor($vars[$rfields['author']]);
            // if (!empty($name))
            //     $editor->SetName($name);
            // if (!empty($author))
            //     $editor->SetAuthor($author);

            // save
            try {

                $editor->Save();

                $this->ModifyAttributes($editor->GetID(), $vars);

                // success
                $output = $this->OutputBox('MsgBox', array(
                    'msg' => 'succeed',
                    'back' => DB3_URL('admin', 'catalog', '', array(
                        'id' => $vars[$rfields['pid']]
                        ))
                    )
                );
            } catch (Exception $ex) {
                // failure
                $output = $this->OutputBox('MsgBox', array(
                    'msg' => 'fail:' . $editor->GetID() . ',' . $ex->getMessage(),
                    'back' => 'back'
                    )
                );
            }

        }
        return $output;
    }

}