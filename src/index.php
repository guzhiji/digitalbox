<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require('modules/common.module.php');
require('modules/pages/PortalPage.class.php');

$portalpage = new PortalPage();
$portalpage->Prepare(include('conf/adminpage.conf.php'));

$portalpage->SetTitle(GetLangData('homepage'));
$portalpage->Show();
