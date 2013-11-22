<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class SaveCatalog extends ProcessModel {

    public function Auth($page) {
        $page->CheckPassport();
        return TRUE;
    }

    public function Process() {

        $id = intval(strGet('id'));
        $username = ''; // $up->GetUID();
        $name = strPost('name');
        $pid = intval(strPost('parent'));
        $output = NULL;

        LoadIBC1Class('CatalogItemEditor', 'data.catalog');
        $editor = new CatalogItemEditor(DB3_SERVICE_CATALOG);

        try {
            if (empty($id)) {
                // ===============create===============
                $editor->Create();
                // set attributes
                $editor->SetUID($username);
                $editor->SetName($name);
                // save
                $editor->Save($pid);
                // success
                $output = $this->OutputBox('MsgBox', array(
                    'msg' => 'succeed',
                    'back' => '?module=catalog&id=' . $pid
                        )
                );
            } else {
                // ===============modify===============
                $editor->Open($id);
                //set changed attributes
                if (!empty($name))
                    $editor->SetName($name);
                // save
                $editor->Save();
                // success
                $output = $this->OutputBox('MsgBox', array(
                    'msg' => 'succeed',
                    'back' => '?module=catalog&id=' . $pid
                        )
                );
            }
        } catch (ServiceException $ex) {
            $output = $this->OutputBox('MsgBox', array(
                'translation' => 'admin',
                'msg' => $ex->getMessage(),
                'back' => 'back'
                    )
            );
        } catch (Exception $ex) {
            // unexpected error
            $output = $this->OutputBox('MsgBox', array(
                'msg' => 'unexpected error: ' . $ex->getMessage(),
                'back' => 'back'
                    )
            );
        }
        return $output;
    }

}