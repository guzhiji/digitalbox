<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class SearchListBox extends Box {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {

        $searchkey = urldecode(strGet("searchkey"));

        $title_maxlen = GetSettingValue("box2_title_maxlen");

        $sql = new SQL_Content();

        $sql->SetSearchKey($searchkey);
        if ($sql->_searchkey != "") {

            $this->SetTitle(GetLangData("search_result"));
            $sql->SetOrder(1);
            $sql->SetChannelID(strGet("channel"));
            $sql->SetClassID(strGet("class"));
            $sql->SetMode(strGet("mode"));

            if ($sql->_classid > 0) {
                global $_classID;
                $_classID = $sql->_classid;
            }
            if ($sql->_channelid > 0) {
                global $_channelID;
                $_channelID = $sql->_channelid;
            }
            //$portalpage->_mode =

            $page_size = GetSettingValue("general_list_maxlen");
            $total_rec = 0;
            $rs = db_query($sql->GetCountQuery());
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0]))
                    $total_rec = intval($list[0]["c"]);
                db_free($rs);
            }
            if ($total_rec > 0) {
                require("modules/PagingBar.class.php");
                $pb = new PagingBar();
                $pb->SetPageCount($total_rec, $page_size);
                //$total_page = $pb->GetPageCount();
                $current_page = $pb->GetPageNumber();

                $rs = db_query($sql->GetSelect() . " LIMIT " . (($current_page - 1) * $page_size) . ",$page_size");
                if ($rs) {

                    $sl = new ListModel(__CLASS__, "search_item");
                    $sl->SetContainer("search_container", array(
                        "TotalCount" => $total_rec,
                        "SearchKey" => htmlspecialchars($searchkey),
                        "PagingBar" => $pb->GetHTML()
                    ));

                    $list = db_result($rs);
                    foreach ($list as $item) {
                        $typename = GetTypeName($item["channel_type"], 1);

                        $sl->AddItem(array(
                            "Content_Name" => $item["content_name"],
                            "Content_Link" => $typename . ".php?id=" . $item["content_id"],
                            "Content_ShortName" => Len_Control($item["content_name"], $title_maxlen),
                            "Content_Type" => GetTypeName($item["channel_type"], 0),
                            "Channel_Name" => $item["channel_name"],
                            "Channel_Link" => "channel.php?type=$typename&id=" . $item["channel_id"],
                            "Channel_ShortName" => Len_Control($item["channel_name"], $title_maxlen),
                            "Class_Name" => $item["class_name"],
                            "Class_Link" => "class.php?type=$typename&id=" . $item["class_id"],
                            "Class_ShortName" => Len_Control($item["class_name"], $title_maxlen),
                            "VCount" => $item["visitor_count"],
                            "Time" => $item["content_time"]
                        ));
                    }
                    db_free($rs);

                    $this->SetContent($sl->GetHTML(), "center", "middle", 5);
                } else {
                    $this->SetContent(GetLangData("error"), "center", "middle", 5);
                }
            } else {

                $html = "<div align=\"center\">" . sprintf(GetLangData("search_notfound"), htmlspecialchars($searchkey)) . "</div>";
                if ($sql->_mode > 0)
                    $html .= "<div align=\"center\">" . sprintf(GetLangData("search_suggestion"), "<a href=\"search.php?mode=0&searchkey=" . urlencode($searchkey) . "\">", "</a>") . "</div>";

                $this->SetContent($html, "center", "middle", 5);
            }
        } else {
            $this->SetTitle(GetLangData("search"));
            $this->SetContent(GetLangData("search_empty"), "center", "middle", 5);
        }
    }

}
