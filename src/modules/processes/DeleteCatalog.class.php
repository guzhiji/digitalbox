<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class DeleteCatalog extends ProcessModel {

    public function Process() {
        session_start(); // debug use
        //$up = getPassport();
        //if (!$up->IsOnline()) {

        $id = intval(strGet('id'));
        $username = ''; // $up->GetUID();
        //$name = strPost('name');
        //$pid = intval(strPost('parent'));
        $output = NULL;

        if (!empty($id)) {

            // create a catalog editor
            LoadIBC1Class('CatalogItemEditor', 'data.catalog');
            $editor = new CatalogItemEditor(SERVICE_CATALOG);
            // get parent id
            $service = $editor->GetDataService();
            $catalog = $service->ReadRecord('catalog', 'clgID', $id, array('clgParentID' => 'ParentID'));

            if (!empty($catalog)) { // found
                $operation = __CLASS__ . "[$id]";

                if (DB3_Operation_IsConfirmed($operation)) {
                    $editor->Open($id);
                    // delete
                    try {
                        $editor->Delete();
                        // success
                        $output = $this->OutputBox('MsgBox', array(
                            'msg' => 'succeed',
                            'back' => '?module=catalog&id=' . $catalog->ParentID
                                )
                        );
                    } catch (Exception $ex) {
                        // failure
                        $output = $this->OutputBox('MsgBox', array(
                            'msg' => 'fail',
                            'back' => 'back'
                                )
                        );
                    }
                } else { // not confirmed
                    $output = $this->OutputBox('ConfirmBox', array(
                        'title' => 'are you sure?',
                        'msg' => 'are you sure?',
                        'operation' => $operation,
                        'yes' => queryString_Append(array('confirmed' => 'yes')),
                        'no' => '?module=catalog&id=' . $catalog->ParentID
                            )
                    );
                }
            }
        }
        if ($output === NULL) {
            $output = $this->OutputBox('MsgBox', array(
                'msg' => 'fail',
                'back' => 'back'
                    )
            );
        }

        //} else {
        //    $this->Output('', array());
        //}
        return $output;
    }

}