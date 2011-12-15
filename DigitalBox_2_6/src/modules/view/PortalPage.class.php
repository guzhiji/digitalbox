<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/PortalPage.class.php
  ------------------------------------------------------------------
 */
require_once("modules/view/Page.class.php");
require_once("modules/view/Navigator.class.php");

class PortalPage extends Page {

    var $_mode;
    var $_channel_id, $_channel_name;
    var $_class_id, $_class_name;
    var $_content_id, $_content_name;

    /**
     * constructor
     */
    function __construct() {
        parent::__construct("portalpage");

        //count
        $filename = "visitorcount.txt";
        if (is_file($filename)) {
            if (strCookie("Visited") != "TRUE" || $this->_visitorcount == 0) {
                $this->_visitorcount++;
                @file_put_contents($filename, strval(intval($this->_visitorcount)));
                @chmod($filename, 0600);
                setcookie(dbPrefix . "_Visited", "TRUE", time() + 60 * 60);
            }
        }
        //variables
        $this->_mode = ""; //content,class,channel
        $this->_channel_id = 0;
        $this->_channel_name = "";
        $this->_class_id = 0;
        $this->_class_name = "";
        $this->_content_id = 0;
        $this->_content_name = "";
        $this->_title = "";
        $this->_prefix = __CLASS__;

        $this->GetDBConn();
        $this->SetCSSFile("main.css");
        $this->SetNavigator($this->NavBar1(), $this->NavBar2());
        $this->SetDescription(GetSettingValue("site_keywords"));
        $this->AddKeywords(GetSettingValue("site_keywords"));

        $scripts = explode(",", GetSettingValue("portal_scripts"));
        foreach ($scripts as $s) {
            $this->AddJSFile("{$s}.js");
        }
    }

    /**
     * set the title of page
     * @param string $title     optional
     */
    public function SetTitle($title="") {
        if ($title == "") {
            switch ($this->_mode) {
                case "content":
                    parent::SetTitle($this->_content_name . " - " . $this->_class_name . " - " . $this->_channel_name);
                    break;
                case "class":
                    parent::SetTitle($this->_class_name . " - " . $this->_channel_name);
                    break;
                case "channel":
                    parent::SetTitle($this->_channel_name);
                    break;
                default:
                    parent::SetTitle("错误");
            }
        } else {
            parent::SetTitle($title);
        }
    }

    /**
     * set channel id and name & adjust mode
     * @param int $id
     * @param string $name
     */
    public function SetChannel($id, $name) {
        if (is_numeric($id) && $id > 0) {
            $this->_channel_id = intval($id);
            $this->_channel_name = $name;
            if ($this->_mode == "error")
                return;
            if ($this->_content_id > 0) {
                $this->_mode = "content";
            } else if ($this->_class_id > 0) {
                $this->_mode = "class";
            } else {
                $this->_mode = "channel";
            }
        }
    }

    /**
     * set class id and name & adjust mode
     * @param int $id
     * @param string $name
     */
    public function SetClass($id, $name) {
        if (is_numeric($id) && $id > 0) {
            $this->_class_id = intval($id);
            $this->_class_name = $name;
            if ($this->_mode == "error")
                return;
            if ($this->_content_id > 0) {
                $this->_mode = "content";
            } else {
                $this->_mode = "class";
            }
        }
    }

    /**
     * set content id and name/title & adjust mode
     * @param int $id
     * @param strint $name
     */
    public function SetContent($id, $name) {
        if (is_numeric($id) && $id > 0) {
            $this->_content_id = intval($id);
            $this->_content_name = $name;
            if ($this->_mode == "error")
                return;
            $this->_mode = "content";
        }
    }

    /**
     * set mode to "error"
     */
    public function RaiseError() {
        $this->_mode = "error";
    }

    public function HasError() {
        return $this->_mode == "error";
    }

    /**
     * main navigation bar
     * @return Navigator 
     */
    protected function NavBar1() {

        $nb = new Navigator("navilink1");

        $nb->AddItem("首 页", "index.php");
        if (GetSettingValue("search_visible"))
            $nb->AddItem("搜 索", "search.php");
        if (GetSettingValue("guestbook_visible"))
            $nb->AddItem("留 言", "guestbook.php");

        $rs = db_query($this->_connid, "select * from channel_info");
        if ($rs) {
            $list = db_result($rs);
            foreach ($list as $item) {
                switch ($item["channel_type"]) {
                    case 1:
                        $nb->AddItem($item["channel_name"], "channel.php?type=article&id=" . $item["id"]);
                        break;
                    case 2:
                        $nb->AddItem($item["channel_name"], "channel.php?type=picture&id=" . $item["id"]);
                        break;
                    case 3:
                        $nb->AddItem($item["channel_name"], "channel.php?type=media&id=" . $item["id"]);
                        break;
                    case 4:
                        $nb->AddItem($item["channel_name"], "channel.php?type=software&id=" . $item["id"]);
                        break;
                    default:
                        $nb->AddItem($item["channel_name"], $item["channel_add"], "", FALSE, FALSE, "_blank");
                }
            }
            db_free($rs);
        }

        return $nb;
    }

    /**
     * 
     * @return Navigator 
     */
    protected function NavBar2() {

        $nb = new Navigator("navilink1");

        if (GetSettingValue("search_visible"))
            $nb->AddItem("搜&nbsp;&nbsp;&nbsp;&nbsp;索", "search.php");

        if (GetSettingValue("guestbook_visible"))
            $nb->AddItem("留&nbsp;&nbsp;&nbsp;&nbsp;言", "guestbook.php");

        $nb->AddItem("联系站长", "mailto:" . GetSettingValue("master_mail"));

        if (GetSettingValue("style_changeable"))
            $nb->AddItem("选择风格", "style.php");

        if (GetSettingValue("friendsite_visible"))
            $nb->AddItem("友情链接", "friendsite.php");

        return $nb;
    }

    /**
     * show navigation box for channels
     * @param bool $GoBackButton 
     */
    public function ShowNavBox1($GoBackButton) {

        $nb = new Navigator("navibutton1");

        $rs = db_query($this->_connid, "select * from channel_info order by channel_name");
        if ($rs) {
            $list = db_result($rs);
            foreach ($list as $item) {
                if ($item["channel_type"] == 0) {
                    $nb->AddItem(
                            $item["channel_name"], $item["channel_add"], ChannelTip($item["channel_name"], GetTypeName($item["channel_type"], 0)), FALSE, TRUE, "_blank"
                    );
                } else {
                    $nb->AddItem(
                            $item["channel_name"], "channel.php?type=" . GetTypeName($item["channel_type"], 1) . "&id=" . $item["id"], ChannelTip($item["channel_name"], GetTypeName($item["channel_type"], 0)), FALSE, TRUE, ""
                    );
                }
            }
            db_free($rs);
        }
        //else {
        //$html = "<div align=\"center\">暂无频道</div>";
        //}

        if ($GoBackButton) {
            $nb->AddItem(
                    "返回首页", "index.php", "", FALSE, TRUE, ""
            );
        }

        $box = new Box(1);
        $box->SetHeight("auto");
        $box->SetTitle("频道导航");
        $box->SetContent($nb->GetHTML(), "center", "middle", 0);
        $this->AddToRight($box);
    }

    /**
     * show navigation box for classes
     * @param int $channel_type 
     */
    public function ShowNavBox2($channel_type) {

        $nb = new Navigator("navibutton1");

        $zh_type = GetTypeName($channel_type, 0);
        $en_type = GetTypeName($channel_type, 1);

        $rs = db_query($this->_connid, "select * from class_info where parent_channel=%d order by class_name", array($this->_channel_id));
        if ($rs) {
            $list = db_result($rs);
            foreach ($list as $item) {
                $nb->AddItem(
                        $item["class_name"], "class.php?type=$en_type&id=" . $item["id"], ClassTip($item["class_name"], $zh_type, $this->_channel_name), FALSE, TRUE, ""
                );
            }
            db_free($rs);

            if ($this->_mode == "class")
                $nb->AddItem(
                        "返回频道", "channel.php?type=$en_type&id=" . $this->_channel_id, ChannelTip($this->_channel_name, $zh_type), FALSE, TRUE, ""
                );

            if ($this->_mode == "content")
                $nb->AddItem(
                        "返回栏目", "class.php?type=$en_type&id=" . $this->_class_id, ClassTip($this->_class_name, $zh_type, $this->_channel_name), FALSE, TRUE, ""
                );
        }
//        else {
//            $html = "暂无栏目";
//        }

        $box = new Box(1);
        $box->SetHeight("auto");
        $box->SetTitle("栏目导航");
        $box->SetContent($nb->GetHTML(), "center", "middle", 0);
        $this->AddToRight($box);
    }

    public function AddAdsBox($adName, $isleft) {
        if ($adName != "") {
            $box = new Box($isleft ? 3 : 1);
            $box->SetTitle("广告");
            $box->SetContent(GetAds($adName), "center", "middle", 10);
            if ($isleft)
                $this->AddToLeft($box);
            else
                $this->AddToRight($box);
        }
    }

    /**
     * show notice board
     */
    public function ShowNoticeBoard() {
        if (GetSettingValue("notice_visible")) {
            $html = "<marquee onmouseover=\"this.stop();\" onmouseout=\"this.start();\" scrollamount=\"2\" scrolldelay=\"100\" direction=\"up\" width=\"150\" height=\"100\" align=\"left\">" . GetSettingValue("notice_text") . "</marquee>";
            $box = new Box(1);
            $box->SetHeight("auto");
            $box->SetTitle("公告栏");
            $box->SetContent($html, "center", "middle", 10);
            $this->AddToRight($box);
        }
    }

    /**
     * show search box
     * @param string $title box title
     * @param int $searchmode 
     * <ul>
     * <li>0 - all</li>
     * <li>1 - article</li>
     * <li>2 - picture</li>
     * <li>3 - media</li>
     * <li>4 - software</li>
     * </ul>
     */
    public function ShowSearchBox($title, $searchmode, $newwindow=TRUE) {
        if (GetSettingValue("search_visible")) {
            $html = "<form method=\"get\" action=\"search.php\"";
            if ($newwindow)
                $html .= " target=\"_blank\"";
            $html .= ">";

            $html .= "<div>";
            $html .= "<input class=\"textinput1\" type=\"text\" name=\"searchkey\"";
            if (strGet("searchkey") != "")
                $html .= " value=\"" . strGet("searchkey") . "\"";
            $html .= " />";
            if ($this->_mode != "")
                $html .= "<input type=\"hidden\" name=\"mode\" value=\"$searchmode\" />";
            $html .= "<input type=\"submit\" class=\"search_button\" value=\"\">";
            $html .= "</div>";
            if ($this->_mode == "") {

                $modenames[0] = "全部内容";
                $modenames[1] = "所有文章";
                $modenames[2] = "所有图片";
                $modenames[3] = "所有媒体";
                $modenames[4] = "所有软件";
                for ($a = 0; $a <= 4; $a++) {
                    $html .= "<div align=\"center\"><input class=\"radio_checkbox\"
                type=\"radio\" name=\"mode\" value=\"{$a}\"";
                    if ($searchmode == $a)
                        $html .= " checked=\"checked\"";
                    $html .= " /> {$modenames[$a]} </div>";
                }
            }else {
                $html .= "<div align=\"center\">";
                if ($this->_mode != "error")
                    $html .= "<input type=\"checkbox\" class=\"radio_checkbox\" name=\"channel\" value=\"" . $this->_channel_id . "\">本频道 ";
                if ($this->_mode == "class" || $this->_mode == "content")
                    $html .= "<input type=\"checkbox\" class=\"radio_checkbox\" name=\"class\" value=\"" . $this->_class_id . "\">本栏目 ";
                $html .= "</div>";
            }
            $html .= "</form>";

            $box = new Box(1);
            $box->SetTitle($title);
            $box->SetContent($html, "center", "middle", 5);
            $this->AddToRight($box);
        }
    }

    /**
     * show mini-calendar box
     */
    public function ShowCalendarBox() {
        if (GetSettingValue("calendar_visible") == FALSE)
            return;
        $html = GetTemplate("calendar");
        $box = new Box(1);
        $box->SetTitle("迷你日历");
        $box->SetContent($html, "center", "middle", 0);
        $this->AddToRight($box);
    }

    /**
     * show friend site list
     */
    public function ShowFriendListBox() {
        if (GetSettingValue("friendsite_visible") == FALSE)
            return;

        require_once("modules/view/SiteList.class.php");
        $sl = new SiteList("sitelist_item_small", "sitelist_empty");
        $sl->SetContainer("sitelist_container_small", 1);
        $sl->Bind($this->_connid, GetSettingValue("site_list_maxlen"));

        $box = new Box(1);
        $box->SetTitle("友情链接");
        $box->SetContent($sl->GetHTML(), "center", "top", 0);
        $this->AddToRight($box);
    }

    /**
     * show vote box
     */
    public function ShowVoteBox() {
        if (GetSettingValue("vote_visible") == FALSE)
            return;
        require_once("modules/view/VoteList.module.php");

        if (GetSettingValue("vote_on")) {
            $html = TransformTpl("voteform_small", array(
                "VoteList" => GetVoteList($this->_connid, 150, TRUE, TRUE)
                    ));
        } else {
            $html = GetVoteList($this->_connid, 150, TRUE);
        }

        $box = new Box(1);
        $box->SetTitle("投票调查");
        $box->SetContent($html, "center", "middle", 10);
        $this->AddToRight($box);
    }

    /**
     * show guestbook messages
     * @param string $key optional
     */
    public function ShowGuestBookBox($key="") {
        if (GetSettingValue("guestbook_visible") == FALSE)
            return;
        require_once("modules/view/CommentList.class.php");
        $cl = new CommentList("commentlist_item");
        $cl->SetContainer("commentlist_container", 1);
        $cl->Bind($this->_connid, $key, GetSettingValue("comment_list_maxlen"));
        if ($cl->type == 3) {
            $box = new Box($cl->type);
            $box->SetTitle("网友评论");
            $box->SetContent($cl->GetHTML(), "left", "middle", 10);
            $this->AddToLeft($box);
        } else {
            $box = new Box($cl->type);
            $box->SetTitle("网友留言");
            $box->SetContent($cl->GetHTML(), "left", "middle", 10);
            $this->AddToRight($box);
        }

        //Len_Control($item["guest_name"], $sgb_maxlen);
    }

}

?>