<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  admin_upload.php
  ------------------------------------------------------------------
 */

function deleteUpload($connid) {
    if (strPost("file") != "") {
        $FileName = GetSystemPath() . "/" . dbUploadPath . "/" . strPost("file");
        @unlink($FileName);
        db_query($connid, "delete from upload_info where upload_filename=\"%s\"", array(strPost("file")));
        return TRUE;
    } else {
        return FALSE;
    }
}

require("modules/view/ContentAdminPage.class.php");
require("modules/view/Box.class.php");
require("modules/common.module.php");

$adminpage = new ContentAdminPage();
$connid = $adminpage->GetDBConn();
$passport = $adminpage->GetPassport();

switch (strGet("function")) {
    case "getupload":
        require("modules/data/Uploader.class.php");
        $upl = new Uploader();
        $upl->getUpload($connid, 5);
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
        $adminpage->ShowInfo($msg, "上传文件", "admin_upload.php");
        break;
    case "delete":
        $msg = "删除失败";
        if (deleteUpload($connid)) {
            $msg = "该文件已经成功删除";
        }
        $adminpage->ShowInfo($msg, "删除文件", "admin_upload.php");
        break;
    case "uploadform":
        $html = TransformTpl("upload", array(
            "Upload_FileTypes" => strtoupper(str_replace(";", " ", GetSettingValue("upload_filetypes"))),
            "Upload_MaxSize" => GetSizeWithUnit(GetSettingValue("upload_maxsize"))
                ));
        $box = new Box(3);
        $box->SetHeight("auto");
        $box->SetTitle("上传管理");
        $box->SetContent($html, "center", "middle", 30);
        $adminpage->AddToLeft($box);
        break;
    default:

        $adminpage->AddJS("function isselected(theForm){for (var i=0; i<theForm.elements.length; i++){ var e = theForm.elements[i];if(e.checked) return true;}return false;}");
        $adminpage->AddJS("function delete_upload(){if(isselected(document.admin_upload)){if(window.confirm(\"您真的要删除此文件吗？\")){document.admin_upload.method=\"post\";document.admin_upload.action=\"admin_upload.php?function=delete\";document.admin_upload.submit();}}else window.alert(\"您未选择对象！\");}");

        require("modules/view/uploadlist.module.php");
        $uplist = &GetUploadList($connid, GetSettingValue("general_list_maxlen"), array(
                    "value" => "上传",
                    "action" => "window.location.href='admin_upload.php?function=uploadform'"
                        ), array(
                    "value" => "删除",
                    "action" => "delete_upload()"
                ));
        $box = new Box(3);
        $box->SetHeight("auto");
        $box->SetTitle("上传管理");
        $box->SetContent($uplist->GetHTML(), "center", "middle", 30);
        $adminpage->AddToLeft($box);
}

//right
$adminpage->ShowNavBox2();
$adminpage->ShowNavBox1();

$adminpage->SetTitle("上传");
$adminpage->Show();

$adminpage->CloseDBConn();
?>
