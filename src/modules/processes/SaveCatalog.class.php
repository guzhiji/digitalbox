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

        $up = new UserPassport(SERVICE_USER); //getPassport()

        if (!$up->IsOnline()) {

            $id = intval(strGet('id'));
            $username = $up->GetUID();
            $name = strPost('name');
            $pid = intval(strPost('parent'));

            LoadIBC1Class('CatalogItemEditor', 'datamodel.catalog');
            $editor = new CatalogItemEditor(SERVICE_CATALOG);
            if (empty($id)) {
                $editor->Create();
                $editor->SetAdminUID($username);
                $editor->SetName($name);
                try {
                    $editor->Save($pid);
                    $this->output_box = '';
                    $this->output_box_params = array();
                } catch (Exception $ex) {
                    $this->output_box = '';
                    $this->output_box_params = array();
                }
            } else {
                $editor->Open($id);
                if (!empty($name))
                    $editor->SetName($name);

                try {
                    $editor->Save();
                    $this->output_box = '';
                    $this->output_box_params = array();
                } catch (Exception $ex) {
                    $this->output_box = '';
                    $this->output_box_params = array();
                }
            }
            $editor->CloseService();
        } else {
            $this->output_box = '';
            $this->output_box_params = array();
        }
    }

}