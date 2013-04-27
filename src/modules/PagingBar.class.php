<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class PagingBar {

    private $pagenumber = 0;
    private $pagecount = 1;
    private $listlength = 5;
    private $cssclass_link = "page_switch1";
    private $cssclass_current = "page_switch3";
    private $cssclass_normal = "page_switch2";
    private $queryname = "page";

    public function SetQueryName($queryname) {
        if ($queryname != "")
            $this->queryname = $queryname;
    }

    public function GetPageNumber() {
        if ($this->pagenumber < 1)
            $this->pagenumber = PageNumber(strGet($this->queryname), $this->pagecount);
        return $this->pagenumber;
    }

    public function SetPageCount($totalrecord, $pagesize) {
        $this->pagecount = PageCount($totalrecord, $pagesize);
    }

    public function GetPageCount() {
        return $this->pagecount;
    }

    public function SetListLength($length) {
        $this->listlength = intval($length);
    }

    public function SetCSSClasses($link, $current, $normal) {
        $this->cssclass_link = $link;
        $this->cssclass_current = $current;
        $this->cssclass_normal = $normal;
    }

    public function GetHTML() {

        $this->GetPageNumber();

//prepare link
        $link = "";
        foreach ($_GET as $key => $value) {
            if ($key == $this->queryname)
                continue;
            if ($link != "")
                $link.="&";
            $link.=$key . "=" . $value;
        }
        if ($link != "")
            $link.="&";
        $link = "?" . $link . "page=";

//total pages
        $html = sprintf(GetLangData("pagination_total"), $this->pagecount);
//back links
        if ($this->pagenumber > 1) {
            $html .= " <a class=\"{$this->cssclass_link}\" href=\"{$link}1\">" . GetLangData("pagination_first") . "</a> "; //end with "page=1"
            $html .= " <a class=\"{$this->cssclass_link}\" href=\"" . $link . ($this->pagenumber - 1) . "\">" . GetLangData("pagination_back") . "</a> ";
        }

//page links
        $start_page = 1;
        $end_page = $this->listlength;
        if (intval($this->listlength / 2) < $this->pagenumber) {
            $start_page = $this->pagenumber - intval($this->listlength / 2);
            $end_page = $start_page + $this->listlength - 1;
        }
        if ($end_page > $this->pagecount)
            $end_page = $this->pagecount;

        for ($c = $start_page; $c <= $end_page; $c++) {
            if (intval($c) == intval($this->pagenumber))
                $html .= " <span class=\"{$this->cssclass_current}\">[{$c}]</span> ";
            else
                $html .= " [<a class=\"{$this->cssclass_link}\" href=\"{$link}{$c}\">{$c}</a>] ";
        }

//next links
        if ($this->pagenumber < $this->pagecount) {
            $html .= " <a class=\"{$this->cssclass_link}\" href=\"" . $link . ($this->pagenumber + 1) . "\">" . GetLangData("pagination_next") . "</a> ";
            $html .= " <a class=\"{$this->cssclass_link}\" href=\"{$link}{$this->pagecount}\">" . GetLangData("pagination_last") . "</a> ";
        }

//output
        return "<span class=\"{$this->cssclass_normal}\">{$html}</span>";
    }

}
