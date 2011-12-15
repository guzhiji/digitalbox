<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  vote.php
  ------------------------------------------------------------------
 */
require("modules/view/PortalPage.class.php");
require("modules/view/Box.class.php");
require("modules/common.module.php");
require("modules/view/VoteList.module.php");

$portalpage = new PortalPage();
$connid = $portalpage->GetDBConn();

$errortips = "";

if (!GetSettingValue("vote_visible")) {
    $errortips.="暂无调查;";
}

//process vote
if (GetSettingValue("vote_on") && strtolower(strGet("command")) == "vote") {
    if (intval(strPost("id")) > 0) {
        if (strCookie("Voted") == "") {
            if (db_query($connid, "update vote_info set vote_value=vote_value+1 where id=%d", array(strPost("id")))) {
                setcookie(dbPrefix . "_Voted", "TRUE", time() + 7 * 24 * 60 * 60);
            }
        } else {
            $errortips.="您已经投过一票了;";
        }
    } else {
        $errortips.="您没有选择任何选项，请重新投一次;";
    }
}

//left
if ($errortips != "") {
    //show error
    $portalpage->ShowInfo(ErrorList($errortips), "错误", "index.php");
} else {

    $portalpage->AddAdsBox(GetSettingValue("ad_1"), TRUE);

    //show form
    if (GetSettingValue("vote_on") && strtolower(strGet("command")) != "vote" && strtolower(strGet("command")) != "result") {

        $html = TransformTpl("voteform", array(
            "VoteList" => GetVoteList($connid, 450, TRUE, TRUE)
                ));

        $box = new Box(3);
        $box->SetTitle("投票调查");
        $box->SetContent($html, "center", "middle", 5);

        $portalpage->AddToLeft($box);
    }

    //show result

    $html = GetVoteList($connid, 450);

    $box = new Box(3);
    $box->SetTitle("投票结果");
    $box->SetContent($html, "center", "middle", 5);
    $portalpage->AddToLeft($box);

    $portalpage->AddAdsBox(GetSettingValue("ad_2"), TRUE);
}

//right
$portalpage->ShowNoticeBoard();
$portalpage->ShowSearchBox("站内搜索", 0);
$portalpage->ShowNavBox1(FALSE);

$portalpage->AddAdsBox(GetSettingValue("ad_3"), FALSE);

$portalpage->ShowCalendarBox();
$portalpage->ShowGuestBookBox("");
$portalpage->ShowFriendListBox();

$portalpage->AddAdsBox(GetSettingValue("ad_4"), FALSE);

$portalpage->SetTitle("投票调查");
$portalpage->Show();

$portalpage->CloseDBConn();
?>
