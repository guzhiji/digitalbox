<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require("modules/common.module.php");
require("modules/pages/PopupPage.class.php");
require("modules/Passport.class.php");
require("modules/uploadlist.module.php");

$passport = new Passport();
if (!$passport->check()) {
    //TODO:debug
    PageRedirect("login.php");
}
$page = new PopupPage();

$uplist = GetUploadList(GetSettingValue("general_list_maxlen"), array(
    "value" => "确定",
    "action" => "ok(this.form)"
        ), array(
    "value" => "取消",
    "action" => "window.close()"
        ));

//TODO JS insertion
//$command = "";
$js = "";
if (strGet("editor") == "ckeditor") {
    //$page->AddJS("function getUrlParam(paramName){var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i');var match = window.location.search.match(reParam);return (match && match.length > 1) ? match[1] : '';}");
    //$command = "window.opener.CKEDITOR.tools.callFunction(getUrlParam('CKEditorFuncNum'), url);";
    $js.=TransformTpl("uploadlist_js_ckeditor", array("UploadPath" => dbUploadPath));
} else {
    //$command = "window.opener.setAddr(url);";
    $js.=TransformTpl("uploadlist_js_editor", array("UploadPath" => dbUploadPath));
}
//$page->AddJS("function ok(theForm){for (var i=0; i<theForm.elements.length; i++){ var e = theForm.elements[i];if(e.checked){var url=\"" . dbUploadPath . "/\"+e.value;" . $command . "window.close();return;}}window.alert(\"您未选择对象！\");}");
$box = new Box("Left", "box3");
$box->SetHeight("auto");
$box->SetTitle("选择文件");
$box->SetContent($js . $uplist->GetHTML(), "center", "middle", 30);
$page->AddBox($box);
$page->SetTitle("选择文件");
$page->Show();
