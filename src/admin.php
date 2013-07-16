<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require('modules/common.module.php');
require('modules/pages/AdminPage.class.php');

$page = new AdminPage();
$page->Prepare(include('conf/adminpage.conf.php'));

$page->SetTitle(GetLangData('homepage'));
$page->Show();
