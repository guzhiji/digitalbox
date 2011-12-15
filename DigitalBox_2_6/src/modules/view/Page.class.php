<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/Page.class.php
  ------------------------------------------------------------------
 */
require_once("modules/data/database.module.php");

/**
 * Description of Page
 *
 * @author guzhiji
 */
class Page {

    private $_pagetpl = "";
    private $_themeid = 1;
    private $_title = "";
    private $_keywords = "";
    private $_description = "";
    private $_meta = "";
    private $_css = "";
    private $_cssfile = "";
    private $_js = "";
    private $_jsfile = array();
    private $_icon = "";
    private $_left = "";
    private $_right = "";
    private $_footertpl = "";
    private $_navigator = NULL;
    private $_subnavigator = NULL;
    private $_starttime;
    protected $_visitorcount;
    protected $_prefix;
    protected $_connid = NULL;

    /**
     * constructor
     * @param string $pagetpl template name for page
     */
    function __construct($pagetpl) {
        //time
        $mtime = explode(" ", microtime());
        $this->_starttime = $mtime[1] + $mtime[0];

        //count
        $filename = "visitorcount.txt";
        if (is_file($filename)) {
            $this->_visitorcount = intval(file_get_contents($filename));
        } else {
            $this->_visitorcount = 1;
            $f = @fopen($filename, "w");
            if ($f) {
                fwrite($f, strval($this->_visitorcount));
                fclose($f);
            }
            @chmod($filename, 0600);
        }

        $this->_themeid = GetSettingValue("style_id");
        $this->_pagetpl = $pagetpl;
        $this->_prefix = __CLASS__;
    }

    /**
     * get the databse connection
     * @return resource     database connection identifier
     */
    public function &GetDBConn() {
        if ($this->_connid == NULL)
            $this->_connid = db_connect();
        return $this->_connid;
    }

    /**
     * close the database connection
     */
    public function CloseDBConn() {
        if ($this->_connid != NULL)
            db_close($this->_connid);
    }

    /**
     * set the title for page
     * @param string $title 
     */
    public function SetTitle($title) {
        $this->_title = $title;
    }

    /**
     * add keywords in &lt;meta&gt;
     * @param string $keywords 
     */
    public function AddKeywords($keywords) {
        $keywords = ProcessKeywords($keywords, ",");
        if ($keywords != NULL && $keywords != "") {
            if ($this->_keywords != "")
                $this->_keywords.=",";
            $this->_keywords.=$keywords;
        }
    }

    /**
     * set description in &lt;meta&gt;
     * @param string $desc 
     */
    public function SetDescription($desc) {
        $this->_description = ProcessKeywords($desc, ",");
    }

    /**
     * add extra &lt;meta&gt; information
     * @param string $name
     * @param string $content 
     */
    public function AddMeta($name, $content) {
        $content = ProcessKeywords($content, ",");
        $this->_meta.="<meta content=\"$content\" name=\"$name\" />\n";
    }

    /**
     * add CSS code fragment
     * @param string $css 
     */
    public function AddCSS($css) {
        $this->_css.=$css;
    }

    /**
     * add a CSS file from /stylesheets directory
     * @param string $cssfile 
     */
    public function SetCSSFile($cssfile) {
        $this->_cssfile = GetResPath($cssfile, "stylesheets", $this->_themeid);
    }

    /**
     * add JavaScript code fragment to &lt;head&gt;
     * @param string $js 
     */
    public function AddJS($js) {
        $this->_js.=$js;
    }

    /**
     * add JavaScript file to &lt;head&gt; from /scripts directory
     * @param string $jsfile 
     */
    public function AddJSFile($jsfile) {
        $jsfile = GetResPath($jsfile, "scripts", $this->_themeid);
        if ($jsfile != "")
            $this->_jsfile[] = $jsfile;
    }

    /**
     * set an image from /images directory as an icon
     * @param stirng $iconfile 
     */
    public function SetIcon($iconfile) {
        $this->_icon = GetResPath($iconfile, "images", $this->_themeid);
    }

    /**
     * add a box to the left column
     * @param Box $box 
     */
    public function AddToLeft(Box $box) {
        if ($box != NULL)
            $this->_left .= $box->GetHTML();
    }

    /**
     * add a box to the right column
     * @param Box $box 
     */
    public function AddToRight(Box $box) {
        if ($box != NULL)
            $this->_right .= $box->GetHTML();
    }

    public function ShowInfo($info, $title=NULL, $back=NULL) {

        if ($back != NULL) {
            if ($back == "back")
                $back = "history.back(1);";
            else
                $back = "location.href='$back'";
            $html = TransformTpl("pageinfo", array(
                "info" => $info,
                "back" => $back
                    ));
        }else {
            $html = $info;
        }
        $box = new Box(3);
        $box->SetHeight("auto");
        $box->SetTitle($title == NULL ? "信 息" : $title);
        $box->SetContent($html, "center", "middle", 10);
        $this->AddToLeft($box);
    }

    protected function GetBanner() {
        $l_url = trim(GetSettingValue("logo_URL"));
        $l_width = GetSettingValue("logo_width");
        $l_height = GetSettingValue("logo_height");
        $b_name = trim(GetSettingValue("banner_name"));
        $b_width = GetSettingValue("banner_width");
        $b_height = GetSettingValue("banner_height");
        $l_visible = !GetSettingValue("logo_hidden")
                && $l_url != ""
                && $l_width > 0
                && $l_height > 0;
        $b_visible = !GetSettingValue("banner_hidden")
                && $b_name != ""
                && $b_width > 0
                && $b_height > 0;
        if ($l_visible)
            return TransformTpl("logobanner", array(
                        "LogoBanner_Height" => $l_height > $b_height ? $l_height : $b_height,
                        "Logo_URL" => $l_url,
                        "Logo_Width" => $l_width,
                        "Logo_Height" => $l_height,
                        "Banner_Width" => $b_width,
                        "Banner_Height" => $b_height,
                        "Banner" => $b_visible ? GetAds($b_name) : ""
                    ));
        else if ($b_visible)
            return TransformTpl("banner", array(
                        "Banner_Width" => $b_width,
                        "Banner_Height" => $b_height,
                        "Banner" => GetAds($b_name)
                    ));
        else
            return "";
    }

    public function SetFooter($footertpl) {
        $this->_footertpl = $footertpl;
    }

    public function SetNavigator(Navigator $navigator, Navigator $subnavigator=NULL) {
        $this->_navigator = $navigator;
        $this->_subnavigator = $subnavigator;
    }

    public function GetHTML() {
        //head
        $head = "";
        if ($this->_icon != "") {
            $mime = "";
            switch (strtolower(GetFileExt($this->_icon))) {
                case "ico":
                    $mime = "image/x-icon";
                    break;
                case "jpg":
                    $mime = "image/jpeg";
                    break;
                case "gif":
                    $mime = "image/gif";
                    break;
                case "png":
                    $mime = "image/png";
                    break;
                case "bmp":
                    $mime = "image/bmp";
                    break;
            }
            if ($mime != "")
                $head.="<link rel=\"shortcut icon\" type=\"{$mime}\" href=\"{$this->_icon}\" />\n";
        }
        if ($this->_cssfile != "")
            $head.="<link rel=\"stylesheet\" type=\"text/css\" href=\"{$this->_cssfile}\" />\n";
        if ($this->_css != "")
            $head.="<style>\n{$this->_css}\n</style>\n";
        if ($this->_js != "")
            $head.="<script language=\"javascript\" type=\"text/javascript\">\n{$this->_js}\n</script>\n";
        foreach ($this->_jsfile as $jsfile) {
            $head.="<script language=\"javascript\" type=\"text/javascript\" src=\"{$jsfile}\"></script>\n";
        }
        $head.=$this->_meta;

        //time
        $mtime = explode(" ", microtime());
        $endtime = $mtime[1] + $mtime[0];

        //footer
        $footer = TransformTpl($this->_footertpl == "" ? "footer" : $this->_footertpl, array(
            "ElapsedTime" => round(($endtime - $this->_starttime) * 1000, 5),
            "MasterName" => GetSettingValue("master_name"),
            "MasterMail" => GetSettingValue("master_mail"),
            "SiteStatement" => GetSettingValue("site_statement"),
            "VisitorCount" => $this->_visitorcount,
            "Version" => dbVersion
                ));

        //output
        return TransformTpl($this->_pagetpl, array(
                    "Version" => "DigitalBox Ver " . dbVersion . ", " . dbAuthor,
                    "Keywords" => $this->_keywords,
                    "Description" => $this->_description,
                    "Title" => $this->_title,
                    "SiteName" => GetSettingValue("site_name"),
                    "Head" => $head,
                    "Navigator" => $this->_navigator == NULL ? "" : $this->_navigator->GetHTML(),
                    "SubNavigator" => $this->_subnavigator == NULL ? "" : $this->_subnavigator->GetHTML(),
                    "Banner" => $this->GetBanner(),
                    "Footer" => $footer,
                    "Left" => $this->_left,
                    "Right" => $this->_right
                        ), $this->_prefix);
    }

    public function Show() {
        echo $this->GetHTML();
    }

}

?>
