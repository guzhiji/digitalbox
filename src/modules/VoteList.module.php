<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

function GetVoteList($width, $withradio = FALSE, $hidedata = FALSE) {
    $rs = db_query("select * from vote_info");
    $html = "<div align=\"center\">" . GetLangData("novote") . "</div>";
    if ($rs) {
        $html = "<div style=\"text-align: left;font-family: 黑体;font-size: 12pt;\">" . htmlspecialchars(GetSettingValue("vote_description")) . "</div>";
        $list = db_result($rs);
        if ($hidedata) {
            foreach ($list as $item) {
                $html.="<div style=\"text-align: left;\">";
                if ($withradio) {
                    $html.="<input type=\"radio\" name=\"id\" class=\"radio_checkbox\" value=\"{$item["id"]}\">";
                }
                $html.=$item["vote_name"] . "</div>";
            }
        } else {
            $count = 0;
            foreach ($list as $item) {
                $count += $item["vote_value"];
            }
            foreach ($list as $item) {
                $w = 0;
                if ($count > 0) {
                    $w = $item["vote_value"] / $count;
                }
                $pct = strval(round($w * 100, 2));
                if (substr($pct, 0, 1) == ".") {
                    $pct = "0" . $pct;
                }
                $html.="<div style=\"text-align: left;\">";
                if ($withradio) {
                    $html.="<input type=\"radio\" name=\"id\" class=\"radio_checkbox\" value=\"{$item["id"]}\">";
                }
                $html.="{$item["vote_name"]}: " . sprintf(GetLangData("voteitem"), $item["vote_value"], $pct) . "</div>";
                $html.="<div style=\"text-align: left;\"><table width={$width} class=\"proportion_bar\"><tr>";
                if ($item["vote_value"] > 0) {
                    $w = round($w * $width);
                    $html.="<td class=\"proportion_valuebar\" width={$w}></td>";
                }
                if ($item["vote_value"] < $count) {
                    $html.="<td></td>";
                }
                $html.="</table></div>";
            }
        }
        db_free($rs);
    }
    return $html;
}
