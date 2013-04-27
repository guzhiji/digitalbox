<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class VoteBox extends Box {

    private $voteTpl;
    private $voteWidth;

    function __construct($size = 1) {
        parent::__construct($size > 1 ? "Left" : "Right", "box" . intval($size));
        if ($size > 2) {
            $this->voteTpl = "voteform";
            $this->voteWidth = 450;
        } else {
            $this->voteTpl = "voteform_small";
            $this->voteWidth = 150;
        }
        if (!GetSettingValue("vote_visible"))
            $this->_status = 2;
    }

    public function CacheBind() {
        if (!GetSettingValue("vote_on")) {
            if ($this->voteWidth == 450) {
                $this->_cacheCategory = "vote";
            } else {
                $this->_cacheCategory = "portalpage";
            }
            $this->_cacheKey = "vote_result";
            $this->_cacheTimeout = -1;
        } else if ($this->voteWidth == 150) {
            $this->_cacheCategory = "portalpage";
            if (strCookie("Voted") == "") {
                $this->_cacheKey = "vote_form";
                $this->_cacheTimeout = -1;
            } else {
                $this->_cacheKey = "vote_result";
                $this->_cacheTimeout = GetSettingValue("cache_timeout");
            }
        }
        $this->_cacheVersion = GetSettingValue("version_vote");
    }

    public function DataBind() {
        global $_voted;
        require_once("modules/VoteList.module.php");
        if (!GetSettingValue("vote_on") || strCookie("Voted") != "" || $_voted) {
            $html = GetVoteList($this->voteWidth, FALSE);
            $this->SetTitle(GetLangData("voteresult"));
        } else {
            $html = TransformTpl($this->voteTpl, array(
                "VoteList" => GetVoteList($this->voteWidth, TRUE, TRUE)
                    ), __CLASS__);
            $this->SetTitle(GetLangData("vote"));
        }
        $this->SetContent($html, "center", "middle", 10);
    }

}
