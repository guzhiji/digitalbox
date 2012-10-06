<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class SearchBox extends BoxModel {

    protected $newwindow;

    function __construct($newwindow = TRUE) {
        parent::__construct("Right", "box1");
        $this->newwindow = $newwindow;
        if (!GetSettingValue("search_visible"))
            $this->_status = 2;
    }

    public function DataBind() {
        $modelist = "";

        global $_classID;
        global $_channelID;

        if (isset($_classID) || isset($_channelID)) {
            if (isset($_channelID)) {
                //this channel
                $modelist .= "<tr><td align=\"left\"><input type=\"checkbox\" 
                    class=\"radio_checkbox\" name=\"channel\" 
                    value=\"" . $_channelID . "\"";
                if (intval(strGet("channel")) == $_channelID)
                    $modelist .= " checked=\"checked\"";
                $modelist .= " />" . GetLangData("thischannelonly") . "</td></tr>";
            }
            if (isset($_classID)) {
                //this class
                $modelist .= "<tr><td align=\"left\"><input type=\"checkbox\" 
                    class=\"radio_checkbox\" name=\"class\" 
                    value=\"" . $_classID . "\"";
                if (intval(strGet("class")) == $_classID)
                    $modelist .= " checked=\"checked\"";
                $modelist .= " />" . GetLangData("thisclassonly") . "</td></tr>";
            }
        } else {
            $modenames = GetLangData("SearchModes");
            $mode = intval(strGet("mode"));
            foreach ($modenames as $a => $desc) {
                $modelist .= "<tr><td align=\"left\"><input class=\"radio_checkbox\"
                type=\"radio\" name=\"mode\" value=\"{$a}\"";
                if ($mode == intval($a))
                    $modelist .= " checked=\"checked\"";
                $modelist .= " /> {$desc} </td></tr>";
            }
        }
        $html = TransformTpl("searchform", array(
            "newwindow" => $this->newwindow ? " target=\"_blank\"" : "",
            "searchkey" => str_replace("\"", "&quot;", str_replace("&", "&amp;", urldecode(strGet("searchkey")))),
            "searchmodelist" => $modelist
                ), __CLASS__);

        $title = GetLangData("search");
        $this->SetTitle($title);

        if (isset($_classID) && intval(strGet("class")) == $_classID) {
            $rs = db_query("select class_name from class_info where id=%d", array($_classID));
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0]))
                    $this->SetTitle($title . ": " . $list[0]["class_name"]);
            }
            db_free($rs);
        } else if (isset($_channelID) && intval(strGet("channel")) == $_channelID) {
            $rs = db_query("select channel_name from channel_info where id=%d", array($_channelID));
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0]))
                    $this->SetTitle($title . ": " . $list[0]["channel_name"]);
            }
            db_free($rs);
        }

        $this->SetContent($html, "center", "middle", 5);
    }

}
