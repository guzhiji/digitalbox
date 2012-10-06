<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

function deleteUpload() {
    if (preg_match("/[a-z0-9\.]{3,}/i", strPost("file"))) {
        $FileName = GetSystemPath() . "/" . dbUploadPath . "/" . strPost("file");
        @unlink($FileName);
        db_query("delete from upload_info where upload_filename=\"%s\"", array(strPost("file")));
        return TRUE;
    } else {
        return FALSE;
    }
}

require("modules/common.module.php");
require("modules/pages/ContentAdminPage.class.php");

$adminpage = new ContentAdminPage();

switch (strGet("function")) {
    case "getupload":
        require("modules/data/Uploader.class.php");
        $upl = new Uploader();
        $upl->getUpload();
        $msg = "";
        if ($upl->FileCount > 0) {
            $msg.="接收了{$upl->FileCount}个文件，一共" . GetSizeWithUnit($upl->TotalSize);
        } else {
            $msg.="未接收到文件";
        }
        if ($upl->error != "") {
            $msg.=";" . $upl->error;
            $msg = ErrorList($msg);
        }
        $adminpage->AddBox(new MsgBox($msg, "上传文件", "admin_upload.php"));
        break;
    case "delete":
        $msg = "删除失败";
        if (deleteUpload()) {
            $msg = "该文件已经成功删除";
        }
        $adminpage->AddBox(new MsgBox($msg, "删除文件", "admin_upload.php"));
        break;
    case "uploadform":
        require("modules/boxes/Admin_UploadForm.class.php");
        $adminpage->AddBox(new Admin_UploadForm());
        break;

//        $html = TransformTpl("upload", array(
//            "Upload_FileTypes" => strtoupper(str_replace(";", " ", GetSettingValue("upload_filetypes"))),
//            "Upload_MaxSize" => GetSizeWithUnit(GetSettingValue("upload_maxsize"))
//                ));
//        $box = new Box(3);
//        $box->SetHeight("auto");
//        $box->SetTitle("上传管理");
//        $box->SetContent($html, "center", "middle", 30);
//        $adminpage->AddToLeft($box);
    default:
        require("modules/boxes/Admin_UploadList.class.php");
        $adminpage->AddBox(new Admin_UploadList());
        break;

//        require("modules/uploadlist.module.php");
//        $uplist = &GetUploadList($connid, GetSettingValue("general_list_maxlen"), array(
//                    "value" => "上传",
//                    "action" => "window.location.href='admin_upload.php?function=uploadform'"
//                        ), array(
//                    "value" => "删除",
//                    "action" => "delete_upload()"
//                ));
//        $box = new Box(3);
//        $box->SetHeight("auto");
//        $box->SetTitle("上传管理");
//        $box->SetContent($uplist->GetHTML(), "center", "middle", 30);
//        $adminpage->AddToLeft($box);
}

$adminpage->SetTitle("上传");
$adminpage->Show();
