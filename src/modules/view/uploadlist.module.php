<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/uploadlist.module.php
  ------------------------------------------------------------------
 */

function &GetUploadList(&$connid, $pagesize, $button1, $button2) {

//count
    $totalcount = 0;
    $rs = db_query($connid, "SELECT count(*) FROM upload_info");
    if ($rs) {
        $list = db_result($rs);
        if (isset($list[0])) {
            $totalcount = $list[0][0];
        }
        db_free($rs);
    }

//pagination
    require_once("modules/view/PagingBar.class.php");
    $pb = new PagingBar();
    $pb->SetPageCount($totalcount, $pagesize);
    $pagecount = $pb->GetPageCount();
    $pagenumber = $pb->GetPageNumber();

//list
    require_once("modules/view/ListView.class.php");
    $uplist = new ListView("uploadlist_item");
    $uplist->SetContainer("uploadlist", array(
        "PagingBar" => $pb->GetHTML(),
        "B1_Value" => $button1["value"],
        "B1_Action" => $button1["action"],
        "B2_Value" => $button2["value"],
        "B2_Action" => $button2["action"]
    ));

    if ($totalcount > 0) {
        $start = $pagesize * ($pagenumber - 1);
        $rs = db_query($connid, "SELECT * FROM upload_info LIMIT {$start},{$pagesize}");
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

?>
