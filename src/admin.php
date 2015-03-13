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
require("modules/pages/AdminPage.class.php");

function ShowCopyright() {
    return "<table><tbody><tr><td rowspan=\"3\"><img src=\"images/logo2.gif\"></td><th align=\"left\">DigitalBox Ver " . dbVersion . "</th></tr><tr><td>&copy; " . dbTime . " 版权所有</td></tr><tr><td>作者：<a href=\"mailto:" . dbMail . "\">" . dbAuthor . "</a></td></tr></tbody></table>";
}

function ShowSiteStats() {

    $stats = array(
        array(
            "频道",
            "个",
            "SELECT count(*) FROM channel_info"
        ),
        array(
            "栏目",
            "个",
            "SELECT count(*) FROM class_info"
        ),
        array(
            "文章",
            "篇",
            "SELECT count(*) FROM article_info WHERE parent_class>0"
        ), array(
            "图片",
            "幅",
            "SELECT count(*) FROM picture_info WHERE parent_class>0"
        ),
        array(
            "媒体",
            "个",
            "SELECT count(*) FROM media_info WHERE parent_class>0"
        ),
        array(
            "软件",
            "个",
            "SELECT count(*) FROM software_info WHERE parent_class>0"
        )
    );
    $html = "目前为止，本站拥有：<br /><ul>";
    foreach ($stats as $item) {
        $rs = db_query($item[2]);
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0])) {
                $html.="<li>{$item[0]}：<span style=\"color: #ff4a00\"><big><strong>{$list[0][0]}</strong></big></span>{$item[1]}</li>";
            }
        }
    }
    $html.="</ul>";
    return $html;
}

$adminpage = new AdminPage();

$box = new Box("Left", "box3");
$box->SetHeight("auto");

$box->SetTitle("管理系统首页");
$box->SetContent(ShowCopyright(), "center", "middle", 20);
$adminpage->AddBox($box);

$box->SetTitle("您好，" . strSession("Admin_UID"));
$box->SetContent(ShowSiteStats(), "left", "middle", 20);
$adminpage->AddBox($box);

$adminpage->SetTitle("首　页");
$adminpage->Show();
