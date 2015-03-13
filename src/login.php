<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require("modules/common.module.php");
require("modules/Passport.class.php");
require("modules/pages/PopupPage.class.php");

$page = new PopupPage();

$passport = new Passport();
switch (strGet("function")) {
    case "login":
        if ($passport->login()) {
            db_close();
            PageRedirect("admin.php");
            exit(0);
        }
        break;
    case "logout":
        $passport->logout();
        break;
    default:
        if ($passport->check()) {
            db_close();
            PageRedirect("admin.php");
            exit(0);
        }
}

//left
if ($passport->error == "") {
    $html = GetTemplate("login");
    $box = new Box("Left", "box3");
    $box->SetHeight("auto");
    $box->SetTitle(GetLangData("login"));
    $box->SetContent($html, "center", "middle", 10);
    $page->AddBox($box);
} else {
    $msg = ErrorList($passport->error);
    $page->AddBox(new MsgBox($msg, GetLangData("error"), "back"));
}


$page->SetTitle(GetLangData("login"));
$page->Show();
