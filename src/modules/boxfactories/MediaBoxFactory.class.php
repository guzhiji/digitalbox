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

class MediaBoxFactory extends BoxFactory {

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

        global $media_add;
        global $media_text;

        require("modules/ContentCtlBar.module.php");

        //info
        $box = new Box("Left", "box3");
        $html = TransformTpl("media_info", array(
            "Name" => $_contentName,
            "Time" => $_contentTime,
            "VCount" => $_contentVCount,
            "Text" => $media_text
                ), __CLASS__);
        $box->SetTitle(GetLangData("info"));
        $box->SetContent($html, "center", "middle", 0);
        $this->AddBox($box);

        //content
        $box = new Box("Left", "box3");
        $w = 500;
        $h = 300;
        $tplname = "";
        $player = "";

        switch (substr($media_add, 0, 3)) {
            case "wp:":
                //windows media player
                $tplname = "media_wp";
                $media_add = substr($media_add, 3);
                break;
            case "rp:":
                //real player
                $tplname = "media_rp";
                $media_add = substr($media_add, 3);
                break;
            case "fp:":
                //flash
                $tplname = "media_fp";
                $media_add = substr($media_add, 3);
                break;
            case "if:":
                //iframe
                $tplname = "media_if";
                $media_add = substr($media_add, 3);
                break;
            default:
                //auto
                switch (strtolower(GetFileExt($media_add))) {
                    case "wmv":
                    case "wma":
                    case "mpg":
                    case "asf":
                    case "mp3":
                    case "mpeg":
                    case "avi":
                        //windows media player
                        $tplname = "media_wp";
                        break;
                    case "rm":
                    case "ra":
                    case "ram":
                        //real player
                        $tplname = "media_rp";
                        break;
                    case "swf":
                        //flash
                        $tplname = "media_fp";
                        break;
                }
        }

        if ($tplname != "") {
            $player = TransformTpl($tplname, array(
                "Address" => $media_add,
                "Width" => $w,
                "Height" => $h
                    ), __CLASS__);
        } else {
            $player = sprintf(GetLangData("unknownmedia"), "<a href=\"" . urlencode($media_add) . "\" target=\"_blank\">", "</a>");
        }

        $html = TransformTpl("media", array(
            "Player" => $player,
            "ControlBar" => GetContentCtlBar()
                ), __CLASS__);

        $box->SetTitle($_contentName);
        $box->SetContent($html, "center", "top", 5);
        $this->AddBox($box);

        //comments
        $this->AddBox(new GuestBookBox($_contentName));
    }

}