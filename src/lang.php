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
require("modules/pages/PortalPage.class.php");
require("modules/ContentList.class.php");
require("modules/boxes/TopBox.class.php");
require("modules/boxes/LangBox.class.php");
require("modules/boxes/AdsBox.class.php");
require("modules/boxes/NoticeBoardBox.class.php");
require("modules/boxes/SearchBox.class.php");
require("modules/boxes/ClassNaviBox.class.php");
require("modules/boxes/CalendarBox.class.php");
require("modules/boxes/VoteBox.class.php");
require("modules/boxes/FriendSiteBox.class.php");

$lang = strtolower(trim(strPost("lang")));
$from_address = strPost("from");
if (strlen($from_address) > 10) {
    $languages = GetSettingValue("languages");
    foreach ($languages as $code => $name) {
        if ($code == $lang) {
            setcookie(dbPrefix . "_Lang", $lang, time() + 7 * 24 * 60 * 60);
            header("Location: " . $from_address);
            exit();
        }
    }
}

$portalpage = new PortalPage();

//left
$portalpage->AddBox(new AdsBox("ad_1", TRUE));

$portalpage->AddBox(new LangBox());

$portalpage->AddBox(new AdsBox("ad_2", TRUE));

//right
$portalpage->AddBox(new NoticeBoardBox());
$portalpage->AddBox(new SearchBox(FALSE));
$portalpage->AddBox(new ChannelNaviBox("Right", TRUE));
$portalpage->AddBox(new AdsBox("ad_3", FALSE));
$portalpage->AddBox(new CalendarBox());
$portalpage->AddBox(new VoteBox());
$portalpage->AddBox(new FriendSiteBox());
$portalpage->AddBox(new AdsBox("ad_4", FALSE));

$portalpage->SetTitle(GetLangData("changelang"));
$portalpage->Show();