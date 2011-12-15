<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  admin_editor_uploadlist.php
  ------------------------------------------------------------------
 */
require("modules/view/PopupPage.class.php");
require("modules/view/Box.class.php");
require("modules/common.module.php");
require("modules/Passport.class.php");
require("modules/view/uploadlist.module.php");

$passport = new Passport();
if (!$passport->check()) {
    //TODO:debug
    PageRedirect("login.php");
}
$page = new PopupPage();
$connid = $page->GetDBConn();

$uplist = &GetUploadList($connid, GetSettingValue("general_list_maxlen"), array(
            "value" => "确定",
            "action" => "ok(this.form)"
                ), array(
            "value" => "取消",
            "action" => "window.close()"
        ));

//js
$command = "";
if (strGet("editor") == "ckeditor") {
    $page->AddJS("function getUrlParam(paramName){var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i');var match = window.location.search.match(reParam);return (match && match.length > 1) ? match[1] : '';}");
    $command = "window.opener.CKEDITOR.tools.callFunction(getUrlParam('CKEditorFuncNum'), url);";
} else {
    $command = "window.opener.setAddr(url);";
}
$page->AddJS("function ok(theForm){for (var i=0; i<theForm.elements.length; i++){ var e = theForm.elements[i];if(e.checked){var url=\"" . dbUploadPath . "/\"+e.value;" . $command . "window.close();return;}}window.alert(\"您未选择对象！\");}");
$box = new Box(3);
$box->SetHeight("auto");
$box->SetTitle("选择文件");
$box->SetContent($uplist->GetHTML(), "center", "middle", 30);
$page->AddToLeft($box);
$page->CloseDBConn();
$page->Show();
?>