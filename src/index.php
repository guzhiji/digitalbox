<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

define('DB3_ROOT_WEB', dirname($_SERVER['SCRIPT_NAME']));
define('DB3_ROOT', getcwd());
//define('IBC1_SYSTEM_ROOT', getcwd() . '/');

require 'conf/main.conf.php';

require 'modules/common.module.php';
require 'modules/pages/MainPage.class.php';

$page = new MainPage();
$page->AddRoute(include('conf/route_public_base.conf.php'));
$page->AddRoute(include('conf/route_public_custom.conf.php'));

$page->SetTitle(GetLangData('homepage'));
$page->Show();
