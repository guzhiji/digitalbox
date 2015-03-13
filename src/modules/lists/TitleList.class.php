<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require_once("modules/uimodel/ListModel.class.php");

class TitleList extends ListModel {

    var $_content_type_en, $_content_type_zh, $_maxlen;
    protected $_content_id, $_content_name, $_content_visitor, $_content_time;
    protected $_class_visible, $_class_id, $_class_name;
    protected $_channel_visible, $_channel_id, $_channel_name;
    protected $_icon_visible, $_extra_mode, $_checkboxname, $_link_type;

    function __construct($checkboxname = "", $maxlen = 30) {
        parent::__construct(__CLASS__, "titlelist_item" . (($checkboxname != "") ? "_checkbox" : ""));
//        $this->SetHeader("titlelist_header", array());
//        $this->SetFooter("titlelist_footer", array());
        $this->SetEmptyItem("titlelist_item_empty");
        $this->SetContainer("titlelist_container");

        $this->_maxlen = $maxlen;
        $this->_channel_visible = FALSE;
        $this->_class_visible = FALSE;
        $this->_icon_visible = FALSE;
        $this->_extra_mode = 0;
        $this->_checkboxname = $checkboxname;
    }

    /**
     * set link type for each item
     * @param int $t
     * <ul>
     * <li>0 - hidden</li>
     * <li>1 - open link in the current window</li>
     * <li>2 - open a new window for the link</li>
     * </ul>
     */
    public function SetLinkType($t) {
        $this->_link_type = $t;
    }

    function SetMaxLength($l) {
        $this->_maxlen = intval($l);
    }

    function ShowChannel($visible) {
        $this->_channel_visible = $visible;
    }

    function ShowClass($visible) {
        $this->_class_visible = $visible;
    }

    function ShowIcon($visible) {
        $this->_icon_visible = $visible;
    }

    function ShowExtraInfo($mode) {
        $this->_extra_mode = $mode;
    }

    function CreateItem($typeid) {
        $this->_content_type_en = GetTypeName($typeid, 1);
        $this->_content_type_zh = GetTypeName($typeid, 0);
        $this->_content_id = 0;
        $this->_content_name = "";
        $this->_content_visitor = -1;
        $this->_content_time = "";
        $this->_class_id = 0;
        $this->_class_name = "";
        $this->_channel_id = 0;
        $this->_channel_name = "";
    }

    function SetItemContent($id, $name, $time, $visitorcount) {
        $this->_content_id = $id;
        $this->_content_name = $name;
        $this->_content_visitor = $visitorcount;
        $this->_content_time = $time;
    }

    function SetItemClass($id, $name) {
        $this->_class_id = $id;
        $this->_class_name = $name;
    }

    function SetItemChannel($id, $name) {
        $this->_channel_id = $id;
        $this->_channel_name = $name;
    }

    function AddItem() {

        //Parent Link
        $pl = "";
        if ($this->_channel_visible) {
            //Channel Link
            $l = $this->_channel_name;
            if ($this->_link_type > 0) {
                $l = "<a " . ($this->_link_type == 2 ? "target=\"_blank\" " : "") .
                        "title=\"" . ChannelTip($this->_channel_name, $this->_content_type_zh) .
                        "\" href=\"channel.php?type={$this->_content_type_en}&id={$this->_channel_id}\">{$l}</a>";
            }
            $pl .= $l;
        }
        if ($this->_class_visible) {
            //Separator
            if ($pl != "")
                $pl .= "-";

            //Class Link
            $l = $this->_class_name;
            if ($this->_link_type > 0) {
                $l = "<a " . ($this->_link_type == 2 ? "target=\"_blank\" " : "") .
                        "title=\"" . ClassTip($this->_class_name, $this->_content_type_zh, $this->_channel_name) .
                        "\" href=\"class.php?type={$this->_content_type_en}&id={$this->_class_id}\">{$l}</a>";
            }
            $pl .= $l;
        }
        if ($pl != "")
            $pl = "[" . $pl . "] ";

        //Icon
        $ico = "";
        if ($this->_icon_visible) {
            $ico = "<img border=\"0\" width=\"15\" height=\"15\" src=\"" . GetThemeResPath("icon_" . $this->_content_type_en . ".gif", "images", GetThemeID()) . "\">";
        }

        //Title
        $t = Len_Control($this->_content_name, $this->_maxlen);
        if ($this->_link_type > 0) {

            //Target
            $target = "";
            if ($this->_link_type == 2)
                $target = "target=\"_blank\" ";
            //TipText
            $tip = ContentTip($this->_content_name, $this->_content_type_zh, $this->_channel_name, $this->_class_name, $this->_content_visitor, $this->_content_time);
            //Link
            $l = "{$this->_content_type_en}.php?id={$this->_content_id}";

            $t = "<a title=\"{$tip}\" href=\"{$l}\"{$target}>{$t}</a>";
        }

        //Extra
        $extra = "";
        switch ($this->_extra_mode) {
            case 1:
                $extra = "(" . GetLangData("time") . ": " . $this->_content_time . ")";
                break;
            case 2:
                $extra = "(" . GetLangData("click") . ": " . $this->_content_visitor . ")";
                break;
            case 3:
                $extra = "(" . $this->_content_time . "," . $this->_content_visitor . ")";
                break;
        }

        if ($this->_checkboxname != "") {
            //with Checkbox
            parent::AddItem(array(
                "CheckboxName" => $this->_checkboxname,
                "ID" => $this->_content_id,
                "ParentLink" => $pl,
                "Icon" => $ico,
                "Title" => $t,
                "Extra" => $extra
            ));
        } else {
            parent::AddItem(array(
                "ParentLink" => $pl,
                "Icon" => $ico,
                "Title" => $t,
                "Extra" => $extra
            ));
        }
    }

}
