<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

function SetDefaultStyle($id) {
    $flag = FALSE;
    $id = intval($id);
    $rs = db_query("SELECT \"true\" FROM style_info WHERE id=%d", array($id));
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
        require_once("modules/cache/PHPCacheEditor.class.php");
        $cm = new PHPCacheEditor("cache", "settings");
        $cm->SetValue("default_style", $id);
        try {
            $cm->Save();
            return TRUE;
        } catch (Exception $ex) {
            
        }
    }
    return FALSE;
}

function SyncStyles() {
    $d = dir("themes");
    $installed = array();
    while (FALSE != ($id = $d->read())) {
        if (is_numeric($id)) {
            require("themes/$id/settings.php");
            $installed[$id] = $Style_Name;
        }
    }
    $toupdate = array();
    $todelete = array();
    $rs = db_query("SELECT id,style_name FROM style_info");
    if ($rs) {
        $list = db_result($rs);
        db_free($rs);
        foreach ($list as $item) {
            if (array_key_exists($item["id"], $installed)) {//exists in the dir
                if ($item["style_name"] != $installed[$item["id"]])//name changed
                    $toupdate[$item["id"]] = $installed[$item["id"]];
                $installed[$item["id"]] = NULL; //remove it
            }else {//not found in the dir
                $todelete[] = $item["id"];
            }
        }
    } else {
        return FALSE;
    }
    $r = TRUE;
    foreach ($todelete as $id) {
        $r = $r && db_query("DELETE FROM style_info WHERE id=%d", array($id));
    }
    foreach ($toupdate as $id => $name) {
        $r = $r && db_query("UPDATE style_info SET style_name=\"%s\" WHERE id=%d", array($name, $id));
    }
    foreach ($installed as $id => $name) {
        if ($name == NULL)//removed
            continue;
        $r = $r && db_query("INSERT INTO style_info (id,style_name,style_imagefolder,style_cssfile) VALUES (%d,\"%s\",\"%s\",\"%s\")", array($id, $name, $id, $id . ".css"));
    }
    return $r;
}

//
//function SyncStyles_backup(&$connid) {
//    //id=dir name
//    //name=settings.php
//    $r = TRUE;
//    $d = dir("themes");
//    while (FALSE !== ($id = $d->read())) {
//        $flag = 0; //new style
//        if (!is_numeric($id))
//            continue;
//        require("themes/$id/settings.php");
//        $rs = db_query( "SELECT style_name FROM style_info WHERE id=%d", array($id));
//        if ($rs) {
//            $list = db_result($rs);
//            if (isset($list[0])) {
//                if ($list[0]["style_name"] != $Style_Name) {
//                    $flag = 1; //name changed
//                } else {
//                    $flag = 2; //existent
//                }
//            }
//            db_free($rs);
//        }
//        switch ($flag) {
//            case 0:
//                if (!db_query( "INSERT INTO style_info (id,style_name,style_imagefolder,style_cssfile) VALUES (%d,\"%s\",\"%s\",\"%s\")", array($id, $Style_Name, $id, $id . ".css"))) {
//                    $r = FALSE;
//                }
//                break;
//            case 1:
//                if (!db_query( "UPDATE style_info SET style_name=\"%s\" WHERE id=%d", array($Style_Name, $id))) {
//                    $r = FALSE;
//                }
//                break;
//        }
//    }
//    $d->close();
//    return $r;
//}