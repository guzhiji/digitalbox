<?php

/*
  ------------------------------------------------------------------
  Copyright 2011-2012 DigitalBox Ver 2.7 (by GuZhiji Studio)
  modules/
  ------------------------------------------------------------------
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
            $this->_cacheExpire = -1;
            $this->_cacheVersion = GetSettingValue("version_vote");
        } else if ($this->voteWidth == 150) {
            $this->_cacheCategory = "portalpage";
            if (strCookie("Voted") == "") {
                $this->_cacheKey = "vote_form";
                $this->_cacheExpire = -1;
            } else {
                $this->_cacheKey = "vote_result";
                $this->_cacheExpire = dbCacheTimeout;
            }
            $this->_cacheVersion = GetSettingValue("version_vote");
        }
    }

    public function DataBind() {

        require_once("modules/VoteList.module.php");

        if (!GetSettingValue("vote_on") || strCookie("Voted") != "") {
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
