<?php

/* ------------------------------------------------------------------
 * This program is a prototype version in the InterBox project,
 *  using the MIT License.
 * http://code.google.com/p/interbox/
 * ------------------------------------------------------------------
 */

/**
 * a box container model
 * 
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.uimodel
 */
class Box {

    protected $_status;
    protected $_error;
    protected $_backpage;
    protected $_type;
    protected $_height;
    protected $_width;
    protected $_title;
    protected $_content;
    protected $_padding;
    protected $_align;
    protected $_valign;
    protected $_tplName;
    protected $_cacheExpire;
    protected $_cacheCategory;
    protected $_cacheKey;
    protected $_cacheVersion;

    function __construct($type, $tplname) {
        $this->_height = "auto";
        $this->_width = "auto";
        $this->_title = "";
        $this->_content = "";
        $this->_padding = 0;
        $this->_align = "left";
        $this->_valign = "top";
        $this->_type = $type;
        $this->_tplName = $tplname;
        $this->_status = 0;
        $this->_error = "";
        $this->_backpage = NULL;
    }

    /**
     * @return int
     * <ul>
     * <li>0 - normal</li>
     * <li>1 - error</li>
     * <li>2 - hidden</li>
     * </ul>
     */
    public function GetStatus() {
        return $this->_status;
    }

    public function GetError() {
        return $this->_error;
    }

    public function GetTitle() {
        return $this->_title;
    }

    public function GetBackPage() {
        return $this->_backpage;
    }

    public function GetType() {
        return $this->_type;
    }

    public function SetHeight($h) {
        $this->_height = $h;
    }

    public function SetWidth($w) {
        $this->_width = $w;
    }

    public function SetTitle($text) {
        $this->_title = $text;
    }

    public function SetAlign($align, $valign) {

        $this->_align = $align;
        $this->_valign = $valign;
    }

    public function SetPadding($padding) {
        $this->_padding = $padding;
    }

    public function SetContent($html, $align = NULL, $valign = NULL, $padding = -1) {
        $this->_content = $html;
        if ($align != NULL)
            $this->_align = $align;
        if ($valign != NULL)
            $this->_valign = $valign;
        if ($padding > -1)
            $this->_padding = $padding;
    }

    public function AppendContent($html) {
        $this->_content .= $html;
    }

    public function CacheBind() {
        //to be over ridden
        $this->_cacheCategory = "";
        $this->_cacheKey = "";
        $this->_cacheExpire = 0;
        $this->_cacheVersion = 0;
    }

    public function DataBind() {
        //to be over ridden
    }

    public function GetRefreshedHTML() {

        $this->DataBind();

        $html = "";
        if ($this->_tplName != "") {
            $html = TransformTpl($this->_tplName, array(
                "Width" => $this->_width,
                "Height" => $this->_height,
                "Title" => $this->_title,
                "Content" => $this->_content,
                "Padding" => $this->_padding,
                "Align" => $this->_align,
                "VAlign" => $this->_valign
                    ), __CLASS__, NULL, "neutral");
        } else {
            $html = $this->_content;
        }

        if (!empty($this->_cacheCategory)) {
            try {
                require_once("modules/cache/PHPCacheEditor.class.php");
                $ce = new PHPCacheEditor(GetCachePath(TRUE), $this->_cacheCategory);
                $ce->SetValue($this->_cacheKey, $html, $this->_cacheExpire, $this->_cacheVersion > 0);
                $ce->Save();
            } catch (Exception $ex) {
                
            }
        }

        return $html;
    }

    public function GetHTML() {
        $result = NULL;
        $this->CacheBind();
        if (!empty($this->_cacheCategory)) {
            require_once("modules/cache/PHPCacheReader.class.php");
            $cr = new PHPCacheReader(GetCachePath(), $this->_cacheCategory);
            $cr->SetRefreshFunction(array($this, "GetRefreshedHTML"));
            $result = $cr->GetValue($this->_cacheKey, $this->_cacheVersion);
        }
        if ($result === NULL)
            return $this->GetRefreshedHTML();
        else
            return $result;
    }

}
