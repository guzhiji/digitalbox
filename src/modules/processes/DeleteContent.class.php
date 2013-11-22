<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

abstract class DeleteContent extends ProcessModel {

    public function Auth($page) {
        $page->CheckPassport();
        return TRUE;
    }

    abstract protected function RemoveAttributes($id);

    public function Process() {

        $id = intval(strGet('id'));
        //$username = ''; // $up->GetUID();
        //$name = strPost('name');
        //$pid = intval(strPost('parent'));
        $output = NULL;

        if (!empty($id)) {

            // create a catalog editor
            LoadIBC1Class('ContentItemEditor', 'data.catalog');
            $editor = new ContentItemEditor(DB3_SERVICE_CATALOG);
            // get parent id
            $service = $editor->GetDataService();
            $content = $service->ReadRecord('content', 'cntID', $id, array('cntCatalogID' => 'ParentID'));

            if (!empty($content)) { // found
                $operation = __CLASS__ . "[$id]";

                if (DB3_Operation_IsConfirmed($operation)) {
                    // confirmed

                    $editor->Open($id);
                    // delete
                    try {
                        $this->RemoveAttributes($id);
                        $editor->Delete();
                        // success
                        $output = $this->OutputBox('MsgBox', array(
                            'msg' => 'succeed',
                            'back' => DB3_URL('admin', 'catalog', '', array('id' => $content->ParentID))
                            )
                        );
                    } catch (Exception $ex) {
                        // failure
                        $output = $this->OutputBox('MsgBox', array(
                            'msg' => 'fail',
                            'back' => DB3_URL('admin', 'catalog', '', array('id' => $content->ParentID))
                            )
                        );
                    }
                } else {
                    // not confirmed

                    $output = $this->OutputBox('ConfirmBox', array(
                        'title' => 'are you sure?',
                        'msg' => 'are you sure?',
                        'operation' => $operation,
                        'yes' => queryString_Append(array('confirmed' => 'yes')),
                        'no' => DB3_URL('admin', 'catalog', '', array('id' => $content->ParentID))
                        )
                    );
                }
            }
        }
        if ($output === NULL) {
            // either catalog not found or id not provided
            $output = $this->OutputBox('MsgBox', array(
                'msg' => 'fail: not found',
                'back' => 'back'
                )
            );
        }

        return $output;
    }

}