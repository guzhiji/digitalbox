<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require("modules/boxes/GuestBookBox.class.php");

class PictureBoxFactory extends BoxFactory {

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

        global $_pictureAddr;
        global $_pictureText;

        //info
        $box = new Box("Left", "box3");
        $html = TransformTpl("picture_info", array(
            "Name" => $_contentName,
            "Time" => $_contentTime,
            "VCount" => $_contentVCount,
            "Text" => $_pictureText
                ), __CLASS__);
        $box->SetTitle(GetLangData("info"));
        $box->SetContent($html, "center", "top", 0);
        $this->AddBox($box);

        //content
        $box = new Box("Left", "box3");
        require("modules/ContentCtlBar.module.php");
        $html = TransformTpl("picture", array(
            "Addr" => $_pictureAddr,
            "ControlBar" => GetContentCtlBar()
                ), __CLASS__);

        $box->SetTitle($_contentName);
        $box->SetContent($html, "center", "top", 5);
        $this->AddBox($box);

        //comments
        $this->AddBox(new GuestBookBox($_contentName));
    }

}