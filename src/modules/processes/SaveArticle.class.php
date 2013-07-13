<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class SaveArticle extends ProcessModel {

    public function Process() {

        //$up = getPassport();
        //if (!$up->IsOnline()) {

        $id = intval(readParam('get|post', 'id'));
        $username = ''; // $up->GetUID();
        $pid = intval(strPost('parent_catalog'));
        $name = strPost('article_name');
        $author = strPost('article_author');
        $text = strPost('article_text');
        $output = NULL;

        LoadIBC1Class('ContentItemEditor', 'data.catalog');
        $editor = new ContentItemEditor(SERVICE_CATALOG);
        if (empty($id)) {
            $editor->Create();
            $editor->SetUID($username);
            $editor->SetName($name);
            $editor->SetAuthor($author);
            try {
                $editor->SetModule('article');
                $editor->Save($pid);

                LoadIBC1Class('UniqueKeyValueEditor', 'data.keyvalue');
                $kveditor = new UniqueKeyValueEditor(SERVICE_ARTICLE, $editor->GetID());
                $kveditor->SetValue('text', $text);
                $kveditor->Save();

                $output = $this->OutputBox('MsgBox', array(
                    'msg' => 'succeed',
                    'back' => queryString(array(
                        'module' => 'catalog',
                        'id' => $pid
                    ))
                        )
                );
            } catch (Exception $ex) {
                $output = $this->OutputBox('MsgBox', array('msg' => 'fail' . $ex->getMessage()));
            }
        } else {
            $editor->Open($id);
            if (!empty($name))
                $editor->SetName($name);
            if (!empty($author))
                $editor->SetAuthor($author);

            try {
                $editor->Save();

                LoadIBC1Class('UniqueKeyValueEditor', 'data.keyvalue');
                $kveditor = new UniqueKeyValueEditor(SERVICE_ARTICLE, $editor->GetID());
                $kveditor->SetValue('text', $text);
                $kveditor->Save();

                $output = $this->OutputBox('MsgBox', array(
                    'msg' => 'succeed',
                    'back' => queryString(array(
                        'module' => 'catalog',
                        'id' => $pid
                    ))
                        )
                );
            } catch (Exception $ex) {
                $output = $this->OutputBox('MsgBox', array('msg' => 'fail:' . $editor->GetID() . ',' . $ex->getMessage()));
            }
        }
        //} else {
        //    $this->Output('', array());
        //}
        return $output;
    }

}