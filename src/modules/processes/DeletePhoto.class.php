<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require 'DeleteContent.class.php';

class DeletePhoto extends DeleteContent {

    protected function RemoveAttributes($id) {

        LoadIBC1Class('UniqueKeyValueEditor', 'data.keyvalue');
        $kveditor = new UniqueKeyValueEditor(DB3_SERVICE_PHOTO, $id);
        $kveditor->RemoveAll();
        $kveditor->Save();

    }

}