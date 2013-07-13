<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
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
        if (empty($id)) {
            $editor->Create();
            $editor->SetUID($username);
            $editor->SetName($name);
            try {
                $editor->Save($pid);
                $output = $this->OutputBox('MsgBox', array('msg' => 'succeed'));
            } catch (Exception $ex) {
                $output = $this->OutputBox('MsgBox', array('msg' => 'fail' . $ex->getMessage()));
            }
        } else {
            $editor->Open($id);
            if (!empty($name))
                $editor->SetName($name);

            try {
                $editor->Save();
                $output = $this->OutputBox('MsgBox', array('msg' => 'succeed'));
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