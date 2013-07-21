<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class SavePhoto extends ProcessModel {

    public function Auth($page) {
        $page->CheckPassport();
        return TRUE;
    }

    public function Process() {

        $id = intval(readParam('get|post', 'id'));
        $username = ''; // $up->GetUID();
        $pid = intval(strPost('parent_catalog'));
        $name = strPost('photo_name');
        $author = strPost('photo_author');
        $filename = strPost('photo_filename');
        $text = strPost('photo_text');
        $output = NULL;

        LoadIBC1Class('ContentItemEditor', 'data.catalog');
        $editor = new ContentItemEditor(DB3_SERVICE_CATALOG);

        if (empty($id)) { // create
            $editor->Create();
            // set attributes
            $editor->SetUID($username);
            $editor->SetName($name);
            $editor->SetAuthor($author);
            // save
            try {
                $editor->SetModule('photo');
                $editor->Save($pid);

                LoadIBC1Class('UniqueKeyValueEditor', 'data.keyvalue');
                $kveditor = new UniqueKeyValueEditor(DB3_SERVICE_PHOTO, $editor->GetID());
                $kveditor->SetValue('filename', $filename);
                $kveditor->SetValue('description', $text);
                $kveditor->Save();

                // success
                $output = $this->OutputBox('MsgBox', array(
                    'msg' => 'succeed',
                    'back' => DB3_URL('admin', 'catalog', '', array(
                        'id' => $pid
                    ))
                        )
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
            $editor->Open($id);
            // set changed attributes
            if (!empty($name))
                $editor->SetName($name);
            if (!empty($author))
                $editor->SetAuthor($author);
            // save
            try {
                $editor->Save();

                LoadIBC1Class('UniqueKeyValueEditor', 'data.keyvalue');
                $kveditor = new UniqueKeyValueEditor(DB3_SERVICE_PHOTO, $editor->GetID());
                $kveditor->SetValue('filename', $filename);
                $kveditor->SetValue('description', $text);
                $kveditor->Save();

                // success
                $output = $this->OutputBox('MsgBox', array(
                    'msg' => 'succeed',
                    'back' => DB3_URL('admin', 'catalog', '', array(
                        'id' => $pid
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