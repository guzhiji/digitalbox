<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class ChannelBox extends Box {

    protected $id;
    protected $name;
    protected $type;

    function __construct($id, $name, $type) {
        parent::__construct("Left", "box3");
        $this->id = intval($id);
        $this->name = $name;
        $this->type = $type;
    }

    public function DataBind() {

        $contentlist = new ContentList();
        $contentlist->SetTitleList(GetSettingValue("channel_titlelist_maxlen"), GetSettingValue("box3_title_maxlen"), TRUE, FALSE, TRUE, 1);
        $contentlist->SetImageList(GetSettingValue("index_grid_maxrow"), 5, 1);
        $contentlist->SetChannel($this->id, $this->name, $this->type);

        $this->SetTitle($this->name);
        $this->SetContent($contentlist->GetHTML(1, 1, NULL, FALSE), "left", "top", 5);
    }

}
