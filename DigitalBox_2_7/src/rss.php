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

echo "<?xml version=\"1.0\" encoding=\"" . dbEncoding . "\" ?>";
echo "<rss version=\"2.0\">";
echo "<channel>";
echo "<title>" . htmlspecialchars(GetSettingValue("site_name")) . "</title>";
echo "<link>" . GetSiteURL() . "</link>";

require("modules/data/sql_content.module.php");
$sql = new SQL_Content();
if (strGet("mode") == 2 || strGet("mode") == "popular")
    $sql->SetOrder(2); //popular
else
    $sql->SetOrder(1); //new
$sql->SetMode(0);
$rs = db_query($sql->GetSelect() . " LIMIT 0," . GetSettingValue("rss_list_maxlen"));
if ($rs) {
    $list = db_result($rs);
    foreach ($list as $item) {
        echo "<item>";
        echo "<title>" . htmlspecialchars($item["content_name"]) . "</title>";
        echo "<link>" . GetSiteURL() . GetTypeName($item["channel_type"], 1) . ".php?id=" . $item["content_id"] . "</link>";
        echo "<pubDate>" . $item["content_time"] . "</pubDate>";
        echo "<category>" . htmlspecialchars($item["channel_name"]) . "</category>";
        echo "<category>" . htmlspecialchars($item["class_name"]) . "</category>";
        //echo "<description>" . GetLangData("click") . ": " . $item["visitor_count"] . "</description>";
        echo "</item>";
    }
    db_free($rs);
}
db_close();
echo "</channel>";
echo "</rss>";