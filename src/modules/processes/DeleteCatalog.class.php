<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class DeleteCatalog extends ProcessModel {

    public function Process() {

        //$up = getPassport();
        //if (!$up->IsOnline()) {

        $id = intval(strGet('id'));
        $username = ''; // $up->GetUID();
        $name = strPost('name');
        $pid = intval(strPost('parent'));
        $output = NULL;

        if (empty($id)) {
            $output = $this->OutputBox('MsgBox', array('msg' => 'fail'));
        } else {

            //create a catalog editor
            LoadIBC1Class('CatalogItemEditor', 'data.catalog');
            $editor = new CatalogItemEditor(SERVICE_CATALOG);
            //open
            $editor->Open($id);
            try {
                //delete
                $editor->Delete();
                $output = $this->OutputBox('MsgBox', array('msg' => 'succeed'));
            } catch (Exception $ex) {
                $output = $this->OutputBox('MsgBox', array('msg' => 'fail'));
            }
        }
        //} else {
        //    $this->Output('', array());
        //}
        return $output;
    }

}