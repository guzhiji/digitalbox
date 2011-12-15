<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/data/style_admin.module.php
  ------------------------------------------------------------------
 */

function SetDefaultStyle(&$connid, $id) {
    $flag = FALSE;
    $id = intval($id);
    $rs = db_query($connid, "SELECT \"true\" FROM style_info WHERE id=%d", array($id));
    if ($rs) {
        $list = db_result($rs);
        if (isset($list[0])) {
            if ($list[0][0] == "true") {
                $flag = TRUE;
            }
        }
        db_free($rs);
    }
    if ($flag) {
        require_once("modules/data/CacheManager.class.php");
        $cm = new CacheManager("settings");
        $cm->SetValue("default_style", $id);
        if ($cm->Save()) {
            return TRUE;
        }
    }
    return FALSE;
}

function SyncStyles(&$connid) {
    //id=dir name
    //name=settings.php
    $r = TRUE;
    $d = dir("themes");
    while (FALSE !== ($id = $d->read())) {
        $flag = 0; //new style
        if (!is_numeric($id))
            continue;
        require("themes/$id/settings.php");
        $rs = db_query($connid, "SELECT style_name FROM style_info WHERE id=%d", array($id));
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0])) {
                if ($list[0]["style_name"] != $Style_Name) {
                    $flag = 1; //name changed
                } else {
                    $flag = 2; //existent
                }
            }
            db_free($rs);
        }
        switch ($flag) {
            case 0:
                if (!db_query($connid, "INSERT INTO style_info (id,style_name,style_imagefolder,style_cssfile) VALUES (%d,\"%s\",\"%s\",\"%s\")", array($id, $Style_Name, $id, $id . ".css"))) {
                    $r = FALSE;
                }
                break;
            case 1:
                if (!db_query($connid, "UPDATE style_info SET style_name=\"%s\" WHERE id=%d", array($Style_Name, $id))) {
                    $r = FALSE;
                }
                break;
        }
    }
    $d->close();
    return $r;
}

?>