<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require 'SaveContent.class.php';

class SavePhoto extends SaveContent {

    function __construct() {
        parent::__construct('photo');
    }

    protected function GetInputMeta() {
        return array(
            array(// alias for required common fields
                'id' => 'id',
                'pid' => 'parent_catalog',
                'name' => 'photo_name',
                'author' => 'photo_author'
            ),
            array(
                'id' => array('get|post', 0, array(
                        'filter' => 'intval'
                    )
                ),
                'parent_catalog' => array('post', 0, array(
                        'filter' => 'intval'
                    )
                ),
                'photo_name' => array('post', '', array(
                        'setter' => 'SetName'
                    )
                ),
                'photo_author' => array('post', '', array(
                        'setter' => 'SetAuthor'
                    )
                ),
                'photo_filename' => array('post', ''),
                'photo_text' => array('post', '')
            )
        );
    }

    protected function CreateAttributes($id, $vars) {

        LoadIBC1Class('UniqueKeyValueEditor', 'data.keyvalue');
        $kveditor = new UniqueKeyValueEditor(DB3_SERVICE_PHOTO, $id);
        $kveditor->SetValue('filename', $vars['photo_filename']);
        $kveditor->SetValue('description', $vars['photo_text']);
        $kveditor->Save();
    }

    protected function ModifyAttributes($id, $vars) {

        LoadIBC1Class('UniqueKeyValueEditor', 'data.keyvalue');
        $kveditor = new UniqueKeyValueEditor(DB3_SERVICE_PHOTO, $id);
        $kveditor->SetValue('filename', $vars['photo_filename']);
        $kveditor->SetValue('description', $vars['photo_text']);
        $kveditor->Save();
    }

}
