<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

function GetUploadList($pagesize, $button1, $button2) {

//count
    $totalcount = 0;
    $rs = db_query("SELECT count(*) FROM upload_info");
    if ($rs) {
        $list = db_result($rs);
        if (isset($list[0])) {
            $totalcount = $list[0][0];
        }
        db_free($rs);
    }

//pagination
    require_once("modules/PagingBar.class.php");
    $pb = new PagingBar();
    $pb->SetPageCount($totalcount, $pagesize);
    //$pagecount = $pb->GetPageCount();
    $pagenumber = $pb->GetPageNumber();

//list
    require_once("modules/uimodel/ListModel.class.php");
    $uplist = new ListModel(NULL, "uploadlist_item");
    $uplist->SetContainer("uploadlist", array(
        "PagingBar" => $pb->GetHTML(),
        "B1_Value" => $button1["value"],
        "B1_Action" => $button1["action"],
        "B2_Value" => $button2["value"],
        "B2_Action" => $button2["action"]
    ));

    if ($totalcount > 0) {
        $start = $pagesize * ($pagenumber - 1);
        $rs = db_query("SELECT * FROM upload_info LIMIT {$start},{$pagesize}");
        if ($rs) {
            $list = db_result($rs);
            foreach ($list as $item) {
                $uplist->AddItem(array(
                    "FileName" => $item["upload_filename"],
                    "FilePath" => dbUploadPath . "/" . $item["upload_filename"],
                    "Description" => $item["upload_filecaption"]
                ));
            }
            db_free($rs);
        }
    }
    return $uplist;
}
