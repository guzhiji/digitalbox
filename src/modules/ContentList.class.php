<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require_once("modules/lists/TitleList.class.php");
require_once("modules/lists/ImageList.class.php");
require_once("modules/data/sql_content.module.php");

class ContentList {

    private $_titleonly, $_extra, $_mode, $_type, $_parentid, $_parentname;
    private $_maxlen, $_maxtitles, $_maximages;
    private $_imagelist, $_titlelist;

    function __construct($checkboxname = "") {
        $this->_mode = "";
        $this->_type = 0;
        $this->_maxlen = 0;
        $this->_maximages = 0;
        $this->_maxtitles = 0;
        $this->_imagelist = new ImageList($checkboxname);
        $this->_titlelist = new TitleList($checkboxname);
    }

    public function GetImageList() {
        return $this->_imagelist;
    }

    public function GetTitleList() {
        return $this->_titlelist;
    }

    public function SetTitleList($maxnum, $maxlen, $showicon, $showchannel, $showclass, $linktype, $titleonly = FALSE, $extra = 1) {
        $this->_maxlen = $maxlen;
        $this->_maxtitles = $maxnum;
        $this->_titlelist->SetMaxLength($maxlen);
        $this->_titlelist->ShowIcon($showicon);
        $this->_titlelist->ShowChannel($showchannel);
        $this->_titlelist->ShowClass($showclass);
        $this->_titlelist->SetLinkType($linktype);
        $this->_titleonly = $titleonly;
        $this->_extra = $extra;
    }

    public function SetImageList($maxrow, $maxcol, $linktype) {
        //$this->_imagelist->_maxrow = $maxrow;
        $this->_maximages = $maxrow * $maxcol;
        $this->_imagelist->SetMaxCols($maxcol);
        $this->_imagelist->SetLinkType($linktype);
        $this->_titleonly = FALSE;
    }

    public function SetClass($id, $name, $contenttype) {
        $this->_mode = "class";
        $this->_parentid = $id;
        $this->_parentname = $name;
        $this->_type = $contenttype;
    }

    public function SetChannel($id, $name, $contenttype) {
        $this->_mode = "channel";
        $this->_parentid = $id;
        $this->_parentname = $name;
        $this->_type = $contenttype;
    }

    /**
     * get the HTML of content list
     * @param int $order
     * <ul>
     * <li>1 - new first</li>
     * <li>2 - popular first</li>
     * <li>3 - by alphabet</li>
     * </ul>
     * @param int $formore
     * <ul>
     * <li>0 - no more</li>
     * <li>1 - button 'more'</li>
     * <li>2 - paging bar</li>
     * </ul>
     * @param string $pagename
     * by default, use portal pages if not specified
     */
    public function GetHTML($order, $formore = 0, $pagename = NULL) {
        $html = "";
        $pb = NULL;

        $sql = new SQL_Content();
        $sql->SetOrder($order);
        $sql->SetMode($this->_type);

        if ($this->_mode == "channel") {
            $mcn = GetLangData("channel");
            $sql->SetChannelID($this->_parentid);
        } else {
            $mcn = GetLangData("class");
            $sql->SetClassID($this->_parentid);
        }

        if ($this->_type == 2 && !$this->_titleonly) {
            if ($this->_maximages <= 0)
                $this->_maximages = 5;

            $sql->AddField("picture_info.picture_add");
            $total_rec = 0;
            $total_page = 1;
            $page_size = $this->_maximages;
            $current_page = 1;

            if ($formore == 2) {
                $rs = db_query($sql->GetCountQuery());
                if ($rs) {
                    $list = db_result($rs);
                    if (isset($list[0])) {
                        $total_rec = intval($list[0]["c"]);
                        require_once("modules/PagingBar.class.php");
                        $pb = new PagingBar();
                        $pb->SetPageCount($total_rec, $page_size);
                        $total_page = $pb->GetPageCount();
                        $current_page = $pb->GetPageNumber();
                    }
                    db_free($rs);
                }
            }

            $rs = db_query($sql->GetSelect() . " LIMIT " . (($current_page - 1) * $page_size) . ",$page_size");
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    //generate list
                    foreach ($list as $item) {
                        $this->_imagelist->CreateItem();
                        $this->_imagelist->SetItemContent($item["content_id"], $item["content_name"], $item["content_time"], -1 /*$item["visitor_count"]*/, $item["picture_add"]);
                        $this->_imagelist->SetItemClass($item["class_id"], $item["class_name"]);
                        $this->_imagelist->SetItemChannel($item["channel_id"], $item["channel_name"]);
                        $this->_imagelist->AddItem();
                    }
                    $html = $this->_imagelist->GetHTML();
                    //more
                    switch ($formore) {
                        case 1://show button [more]

                            if ($pagename == NULL)
                                $pagename = "{$this->_mode}.php?type=picture&id={$this->_parentid}";

                            $html = TransformTpl("contentlist_morebtn", array(
                                "HTML" => $html,
                                "TipText" => ($this->_mode == "channel") ? ChannelTip($this->_parentname, GetTypeName(2, 0)) : ClassTip($this->_parentname, GetTypeName(2, 0), $this->_parentname),
                                "Page" => $pagename,
                                "MoreBtn" => GetThemeResPath("button/more.gif", "images", GetThemeID())
                                    ), __CLASS__);

                            break;
                        case 2://paging
                            require_once("modules/PagingBar.class.php");
                            $pb = new PagingBar();
                            $pb->SetPageCount($total_rec, $page_size);

                            $html = TransformTpl("contentlist_pagination", array(
                                "HTML" => $html,
                                "ListName" => $mcn,
                                "Count" => $total_rec,
                                "ContentType" => GetTypeName(2, 0),
                                "PagingBar" => $pb == NULL ? "" : $pb->GetHTML()
                                    ), __CLASS__);

                            break;
                    }
                }
                db_free($rs);
            }
        }else {
            if ($this->_maxlen <= 0)
                $this->_maxlen = GetSettingValue("box3_title_maxlen");
            if ($this->_maxtitles <= 0)
                $this->_maxtitles = GetSettingValue("general_list_maxlen");

            $total_rec = 0;
            $total_page = 1;
            $page_size = $this->_maxtitles;
            $current_page = 1;

            if ($formore == 2) {
                $rs = db_query($sql->GetCountQuery());
                if ($rs) {
                    $list = db_result($rs);
                    if (isset($list[0])) {
                        $total_rec = intval($list[0]["c"]);
                        require_once("modules/PagingBar.class.php");
                        $pb = new PagingBar();
                        $pb->SetPageCount($total_rec, $page_size);
                        $total_page = $pb->GetPageCount();
                        $current_page = $pb->GetPageNumber();
                    }
                    db_free($rs);
                }
            }


            $rs = db_query($sql->GetSelect() . " LIMIT " . (($current_page - 1) * $page_size) . ",$page_size");
            if ($rs) {
                $this->_titlelist->SetMaxLength($this->_maxlen);
                //$this->_titlelist->ShowIcon(TRUE);
                $this->_titlelist->ShowExtraInfo($this->_extra);


                $list = db_result($rs);
                if (isset($list[0])) {
                    //generate list
                    foreach ($list as $item) {
                        //$this->_titlelist->CreateItem($this->_type);
                        $this->_titlelist->CreateItem($item["channel_type"]);
                        $this->_titlelist->SetItemContent($item["content_id"], $item["content_name"], $item["content_time"], -1 /*$item["visitor_count"]*/);
                        $this->_titlelist->SetItemClass($item["class_id"], $item["class_name"]);
                        $this->_titlelist->SetItemChannel($item["channel_id"], $item["channel_name"]);
                        $this->_titlelist->AddItem();
                    }
                    $html = $this->_titlelist->GetHTML();
                    //more
                    switch ($formore) {
                        case 1://show button [more]

                            if ($pagename == NULL)
                                $pagename = $this->_mode . ".php?type=" . $this->_titlelist->_content_type_en . "&id=" . $this->_parentid;

                            $html = TransformTpl("contentlist_morebtn", array(
                                "HTML" => $html,
                                "TipText" => ($this->_mode == "channel") ? ChannelTip($this->_parentname, GetTypeName($this->_type, 0)) : ClassTip($this->_parentname, GetTypeName($this->_type, 0), $this->_parentname),
                                "Page" => $pagename,
                                "MoreBtn" => GetThemeResPath("button/more.gif", "images", GetThemeID())
                                    ), __CLASS__);

                            break;
                        case 2://paging

                            $html = TransformTpl("contentlist_pagination", array(
                                "HTML" => $html,
                                "ListName" => $mcn,
                                "Count" => $total_rec,
                                "ContentType" => GetTypeName($this->_type, 0),
                                "PagingBar" => $pb == NULL ? "" : $pb->GetHTML()
                                    ), __CLASS__);

                            break;
                    }
                }
                db_free($rs);
            }
        }
        return $html;
    }

}
