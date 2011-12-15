<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  login.php
  ------------------------------------------------------------------
 */
require("modules/view/PortalPage.class.php");
require("modules/common.module.php");
require("modules/Passport.class.php");
require("modules/view/Box.class.php");

$portalpage = new PortalPage();
$connid = $portalpage->GetDBConn();

$passport = new Passport($connid);
switch (strGet("function")) {
    case "login":
        if ($passport->login()) {
            db_close($connid);
            PageRedirect("admin.php");
            exit(0);
        }
        break;
    case "logout":
        $passport->logout();
        break;
    default:
        if ($passport->check()) {
            db_close($connid);
            PageRedirect("admin.php");
            exit(0);
        }
}

$portalpage->AddMeta("robots", "noindex,nofollow");
//left
if ($passport->error == "") {
    $html = GetTemplate("login");
    $box = new Box(3);
    $box->SetHeight("auto");
    $box->SetTitle("登 录");
    $box->SetContent($html, "center", "middle", 10);
    $portalpage->AddToLeft($box);
} else {
    $portalpage->ShowInfo(ErrorList($passport->error), "错 误", "back");
}

//right
$portalpage->ShowNoticeBoard();

$portalpage->ShowNavBox1(TRUE);

$portalpage->SetTitle("登录管理中心");
$portalpage->Show();

$portalpage->CloseDBConn();
?>