<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/VoteList.module.php
  ------------------------------------------------------------------
 */

/**
 *
 * @author guzhiji
 */
function GetVoteList(&$connid, $width, $withradio=FALSE, $hidedata=FALSE) {
    $rs = db_query($connid, "select * from vote_info");
    if (!$rs) {
        $html = "暂无调查";
    } else {
        $html = "<div style=\"text-align: left;font-family: 黑体;font-size: 12pt;\">" . GetSettingValue("vote_description") . "</div>";
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
                $html.="{$item["vote_name"]}：共{$item["vote_value"]}票，占{$pct}%</div>";
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

?>