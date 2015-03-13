<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/imagelist.module.php
  ------------------------------------------------------------------
 */
require_once("modules/view/GridView.class.php");

class ImageList extends GridView {

    protected $_content_id, $_content_name, $_content_visitor, $_content_time;
    protected $_class_id, $_class_name;
    protected $_channel_id, $_channel_name;
    protected $_link_type, $_checkboxname;

    function __construct($checkboxname="", $maxcol=5) {
        parent::__construct("imagelist_item" . (($checkboxname != "") ? "_checkbox" : ""), "imagelist_row", "imagelist_table", $maxcol);

        $this->SetRestItem("imagelist_item_rest", array());

        $this->_link_type = 0;
        $this->_checkboxname = $checkboxname;
        $this->_prefix = __CLASS__;
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

    public function SetMaxCols($col) {
        $this->_coln = $col;
    }

    public function CreateItem() {
        $this->_content_id = 0;
        $this->_content_name = "";
        $this->_content_visitor = -1;
        $this->_content_time = "";
        $this->_class_id = 0;
        $this->_class_name = "";
        $this->_channel_id = 0;
        $this->_channel_name = "";
    }

    public function SetItemContent($id, $name, $time, $visitorcount, $url) {
        $this->_content_id = $id;
        $this->_content_name = $name;
        $this->_content_visitor = $visitorcount;
        $this->_content_time = $time;
        $this->_content_url = $url;
    }

    public function SetItemClass($id, $name) {
        $this->_class_id = $id;
        $this->_class_name = $name;
    }

    public function SetItemChannel($id, $name) {
        $this->_channel_id = $id;
        $this->_channel_name = $name;
    }

    public function AddItem() {

        //LinkHead
        $lh = "";
        if ($this->_link_type > 0) {
            $lh = "<a";
            if ($this->_link_type == 2)
                $lh.=" target=\"_blank\"";
            $lh.=" title=\"" . ContentTip($this->_content_name, "图片", $this->_channel_name, $this->_class_name, $this->_content_visitor, $this->_content_time) . "\"";
            $lh.=" href=\"picture.php?id=" . $this->_content_id . "\">";
        }

        //LinkEnd
        $le = $lh == "" ? "" : "</a>";

        //Image
        //$this->_content_url
        //Title
        //$this->_content_name

        if ($this->_checkboxname != "") {
            //with Checkbox
            parent::AddItem(array(
                "LinkHead" => $lh,
                "LinkEnd" => $le,
                "Image" => $this->_content_url,
                "Title" => $this->_content_name,
                "CheckboxName" => $this->_checkboxname,
                "ID" => $this->_content_id
            ));
        } else {
            parent::AddItem(array(
                "LinkHead" => $lh,
                "LinkEnd" => $le,
                "Image" => $this->_content_url,
                "Title" => $this->_content_name
            ));
        }
    }

    public function GetHTML() {
        if ($this->_itemcount == 0) {
            $this->SetEmptyItem("imagelist_item_empty", array(
                "NoPic" => GetResPath("nopic.gif", "images", GetSettingValue("style_id"))
            ));
        }

        return parent::GetHTML();
    }

}

?>
