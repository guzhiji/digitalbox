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

    public function Process() {

        //$up = getPassport();
        //if (!$up->IsOnline()) {

        $id = intval(strGet('id'));
        $username = ''; // $up->GetUID();
        $name = strPost('name');
        $pid = intval(strPost('parent'));
        $output = NULL;

        LoadIBC1Class('CatalogItemEditor', 'data.catalog');
        $editor = new CatalogItemEditor(SERVICE_CATALOG);

        if (empty($id)) { // create
            $editor->Create();
            // set attributes
            $editor->SetUID($username);
            $editor->SetName($name);
            // save
            try {
                $editor->Save($pid);
                // success
                $output = $this->OutputBox('MsgBox', array(
                    'msg' => 'succeed',
                    'back' => '?module=catalog&id=' . $pid
                        )
                );
            } catch (Exception $ex) {
                // failure
                $output = $this->OutputBox('MsgBox', array(
                    'msg' => 'fail' . $ex->getMessage(),
                    'back' => 'back'
                        )
                );
            }
        } else { // modify
            $editor->Open($id);
            //set changed attributes
            if (!empty($name))
                $editor->SetName($name);
            // save
            try {
                $editor->Save();
                // success
                $output = $this->OutputBox('MsgBox', array(
                    'msg' => 'succeed',
                    'back' => '?module=catalog&id=' . $pid
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
        //} else {
        //    $output = $this->Output('', array());
        //}
        return $output;
    }

}