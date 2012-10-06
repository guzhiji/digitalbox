<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require("modules/boxes/GuestBookBox.class.php");

class ArticleBoxFactory extends BoxFactory {

    function __construct() {
        parent::__construct("Left");
    }

    public function DataBind() {

        //global $_channelID;
        //global $_channelName;
        //global $_channelType;
        //global $_classID;
        //global $_className;
        //global $_contentID;
        global $_contentName;
        global $_contentTime;
        global $_contentVCount;

        global $_articleAuthor;
        global $_articleText;

        //content
        $box = new Box("Left", "box3");
        require("modules/ContentCtlBar.module.php");
        $html = TransformTpl("article", array(
            "Name" => $_contentName,
            "Author" => $_articleAuthor,
            "Time" => $_contentTime,
            "VCount" => $_contentVCount,
            "Text" => $_articleText,
            "ControlBar" => GetContentCtlBar()
                ), __CLASS__);
        $box->SetTitle($_contentName);
        $box->SetContent($html, "center", "top", 5);
        $this->AddBox($box);

        //comments
        $this->AddBox(new GuestBookBox($_contentName));
    }

}