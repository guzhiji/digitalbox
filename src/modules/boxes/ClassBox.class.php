<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class ClassBox extends Box {

    protected $id;
    protected $name;
    protected $type;

    function __construct($id, $name, $type) {
        parent::__construct("Left", "box2");
        $this->id = intval($id);
        $this->name = $name;
        $this->type = $type;
    }

    public function DataBind() {
        $contentlist = new ContentList();
        $contentlist->SetTitleList(GetSettingValue("class_titlelist_maxlen"), GetSettingValue("box2_title_maxlen"), TRUE, FALSE, FALSE, 1, TRUE);
        $contentlist->SetClass($this->id, $this->name, $this->type);

        $this->SetTitle($this->name);
        $this->SetHeight(200);
        $this->SetContent($contentlist->GetHTML(1, 1, NULL, FALSE), "left", "top", 10);
    }

}
