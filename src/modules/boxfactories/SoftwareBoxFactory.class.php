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

class SoftwareBoxFactory extends BoxFactory {

    function __construct() {
        parent::__construct("Left");
    }

    public function DataBind() {

        //global $_channelID;
        //global $_channelName;
        //global $_channelType;
        //global $_classID;
        //global $_className;

        global $_contentID;
        global $_contentName;
        global $_contentTime;
        global $_contentVCount;

        global $software_producer;
        global $software_language;
        global $software_grade;
        global $software_size;
        global $software_environment;
        global $software_type;
        global $software_text;

        //info
        $box = new Box("Left", "box3");
        $stars = "";
        for ($a = 0; $a < $software_grade; $a++)
            $stars .= "<img border=0 src=\"" . GetThemeResPath("star.gif", "images") . "\" />";
        require("modules/ContentCtlBar.module.php");
        $html = TransformTpl("software", array(
            "Name" => $_contentName,
            "Producer" => $software_producer,
            "Type" => $software_type,
            "Language" => $software_language,
            "Size" => GetSizeWithUnit($software_size),
            "Environment" => $software_environment,
            "Grade" => $stars,
            "Time" => $_contentTime,
            "ID" => $_contentID,
            "Count" => $_contentVCount,
            "ControlBar" => GetContentCtlBar()
                ), __CLASS__);

        $box->SetTitle($_contentName);
        $box->SetContent($html, "center", "top", 0);
        $this->AddBox($box);

        //introduction
        if ($software_text != "") {
            $html = "<div style=\"word-wrap: break-word;overflow: auto;width: 520px;text-align: left;\">{$software_text}</div>";
            $box = new Box("Left", "box3");
            $box->SetTitle(GetLangData("intro"));
            $box->SetContent($html, "center", "middle", 0);
            $this->AddBox($box);
        }

        //comments
        $this->AddBox(new GuestBookBox($_contentName));
    }

}