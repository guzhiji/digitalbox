<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/GuestBook.class.php
  ------------------------------------------------------------------
 */

class GuestBook {

    var $_connid, $_portalpage, $_pagesize, $_absolutepage, $error, $mode;

    function __construct(PortalPage &$portalpage, $mode, $pagesize) {
        $this->_connid = &$portalpage->GetDBConn();
        $this->_portalpage = &$portalpage;
        $this->_pagesize = intval($pagesize);
        $this->mode = $mode;
    }

    public function ShowControlBox() {
        //javascript
        //$this->_portalpage->AddJS("function save_message(){document.guestbook.method=\"post\";document.guestbook.action=\"guestbook.php?function=add\";document.guestbook.submit();}");
        //$this->_portalpage->AddJS("function reset_message(){document.guestbook.reset();document.all.head_viewer.src=\"images/head/1.gif\";}");
        //get record count
        $record_count = 0;
        $rs = db_query($this->_connid, "SELECT count(*) FROM guest_info");
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0])) {
                $record_count = $list[0][0];
            }
            db_free($rs);
        }

        $tip1 = "保存";
        $image1 = "button/save.gif";
        $link1 = "javascript:save_message()";
        $tip2 = "重写";
        $image2 = "button/reset.gif";
        $link2 = "javascript:reset_message()";
        if ($this->mode != "add") {
            $page_count = PageCount($record_count, $this->_pagesize);
            $this->_absolutepage = PageNumber(strGet("page"), $page_count);
            if ($this->_absolutepage > 1) {
                $tip1 = "共" . $record_count . "条留言，上一页" . strval($this->_absolutepage - 1) . "/" . $page_count;
                $image1 = "button/back.gif";
                $link1 = "guestbook.php?page=" . strval($this->_absolutepage - 1);
            } else {
                $tip1 = "共" . $record_count . "条留言，当前第一页";
                $image1 = "button/back2.gif";
                $link1 = "";
            }
            if ($this->_absolutepage < $page_count) {
                $tip2 = "共" . $record_count . "条留言，下一页" . strval($this->_absolutepage + 1) . "/" . $page_count;
                $image2 = "button/next.gif";
                $link2 = "guestbook.php?page=" . strval($this->_absolutepage + 1);
            } else {
                $tip2 = "共" . $record_count . "条留言，当前最后一页";
                $image2 = "button/next2.gif";
                $link2 = "";
            }
        }

        $image1 = GetResPath($image1, "images", GetSettingValue("style_id"));
        $image2 = GetResPath($image2, "images", GetSettingValue("style_id"));
        if ($link1 != "")
            $link1 = "<a href=\"$link1\">";
        if ($link2 != "")
            $link2 = "<a href=\"$link2\">";

        $html = TransformTpl("guestbook_controlbox", array(
            "Image_View" => GetResPath("button/look.gif", "images", GetSettingValue("style_id")),
            "Image_Write" => GetResPath("button/write.gif", "images", GetSettingValue("style_id")),
            "Image_B1" => $image1,
            "Image_B2" => $image2,
            "Tip_B1" => $tip1,
            "Tip_B2" => $tip2,
            "LinkStart_B1" => $link1,
            "LinkEnd_B1" => $link1 != "" ? "</a>" : "",
            "LinkStart_B2" => $link2,
            "LinkEnd_B2" => $link2 != "" ? "</a>" : ""
                ), "GuestBook");

        $box = new Box(3);
        $box->SetHeight("auto");
        $box->SetTitle("留 言 本");
        $box->SetContent($html, "center", "middle", 2);
        $this->_portalpage->AddToLeft($box);
    }

    public function ShowEditor() {
        //$this->_portalpage->AddJS("function show_head(a){a.src=\"images/head/\"+document.guestbook.guest_head.value+\".gif\";}");

        $html = TransformTpl("guestbook_editor", array(
            "Editor_Title" => str_replace(chr(34), "&quot;", urldecode(strGet("reply")))
                ), "GuestBook");
        $box = new Box(3);
        $box->SetHeight("auto");
        $box->SetTitle("留 言");
        $box->SetContent($html, "center", "middle", 2);
        $this->_portalpage->AddToLeft($box);
    }

    /**
     * involked after ShowControlBox()
     */
    public function ShowCommentList() {
        $rs = NULL;
        $start = ($this->_absolutepage - 1) * $this->_pagesize;
        $guest_id = strGet("id");
        if ($guest_id != "") {
            $rs = db_query($this->_connid, "SELECT * FROM guest_info WHERE id=%d", array($guest_id));
        } else {
            $rs = db_query($this->_connid, "SELECT * FROM guest_info ORDER BY guest_date DESC LIMIT {$start},{$this->_pagesize}");
        }
        $list = NULL;
        if ($rs) {
            $list = db_result($rs);
            if (!isset($list[0])) {
                $list = NULL;
            }
            db_free($rs);
        }
        if ($list == NULL) {
            //error information
            $box = new Box(3);
            $box->SetHeight("auto");
            if ($guest_id != "") {
                $box->SetTitle("错误");
                $box->SetContent("找不到此留言", "center", "middle", 2);
            } else {
                $box->SetTitle("请留言！");
                $box->SetContent("没有留言", "center", "middle", 2);
            }
            $this->_portalpage->AddToLeft($box);
        } else {
            //comment list

            foreach ($list as $item) {

                $homepage = "";
                if (strlen($item["guest_homepage"]) > 7) {
                    $homepage = "<a target=\"_blank\" href=\"{$item["guest_homepage"]}\"><img title=\"{$item["guest_homepage"]}\" border=0 src=\"" . GetResPath("url.gif", "images", GetSettingValue("style_id")) . "\" /></a>";
                } else {
                    $homepage = "<img title=\"未填\" border=\"0\" src=\"" . GetResPath("nourl.gif", "images", GetSettingValue("style_id")) . "\" />";
                }

                $mail = "";
                if (strlen($item["guest_mail"]) > 5) {
                    $mail = "<a href=\"mailto:{$item["guest_mail"]}\"><img title=\"{$item["guest_mail"]}\" border=0 src=\"" . GetResPath("email.gif", "images", GetSettingValue("style_id")) . "\" /></a>";
                } else {
                    $mail = "<img title=\"未填\" border=\"0\" src=\"" . GetResPath("noemail.gif", "images", GetSettingValue("style_id")) . "\" />";
                }

                $replylink = "guestbook.php?mode=add&reply=" . urlencode("re:留言《" . str_replace("《", "〈", str_replace("》", "〉", $item["guest_title"])) . "》");

                $html = TransformTpl("guestbook_message", array(
                    "Head" => "images/head/" . $item["guest_head"] . ".gif",
                    "Name" => $item["guest_name"],
                    "Text" => $item["guest_text"],
                    "Homepage" => $homepage,
                    "Mail" => $mail,
                    "Link_Reply" => $replylink,
                    "Image_Reply" => GetResPath("reply.gif", "images", GetSettingValue("style_id")),
                    "Image_Time" => GetResPath("time.gif", "images", GetSettingValue("style_id")),
                    "Date" => $item["guest_date"]
                        ), "Guest");

                $box = new Box(3);
                $box->SetHeight("auto");
                $box->SetTitle($item["guest_title"]);
                $box->SetContent($html, "center", "middle", 2);
                $this->_portalpage->AddToLeft($box);
            }
        }
    }

}