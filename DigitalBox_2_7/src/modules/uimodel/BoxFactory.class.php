<?php

/* ------------------------------------------------------------------
 * This program is a prototype version in the InterBox project,
 *  using the MIT License.
 * http://code.google.com/p/interbox/
 * ------------------------------------------------------------------
 */

/**
 * 
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.uimodel
 */
class BoxFactory {

    protected $_cacheExpire;
    protected $_cacheCategory;
    protected $_cacheKey;
    protected $_cacheVersion;
    protected $_cacheRandFactor;
    protected $_boxType;
    protected $_html;

    function __construct($type) {
        $this->_boxType = $type;
        $this->_html = "";
    }

    public function GetType() {
        return $this->_boxType;
    }

    public function CacheBind() {
        //to be over ridden
        $this->_cacheCategory = "";
        $this->_cacheKey = "";
        $this->_cacheExpire = 0;
        $this->_cacheVersion = 0;
        $this->_cacheRandFactor = 1;
    }

    public function AddBox(Box $box) {
        if ($box->GetType() == $this->_boxType) {
            if (empty($this->_html))
                $this->_html = $box->GetHTML();
            else
                $this->_html.=$box->GetHTML();
        }
    }

    public function DataBind() {
        //to be over ridden
    }

    public function GetRefreshedHTML() {
        $this->DataBind();

        if (!empty($this->_cacheCategory)) {

            try {
                require_once("modules/cache/PHPCacheEditor.class.php");
                $ce = new PHPCacheEditor(GetCachePath(TRUE), $this->_cacheCategory);
                $ce->SetValue($this->_cacheKey, $this->_html, $this->_cacheExpire, $this->_cacheVersion > 0);
                $ce->Save();
            } catch (Exception $ex) {
                
            }
        }

        return $this->_html;
    }

    public function GetHTML() {
        $html = NULL;
        $this->CacheBind();
        if (!empty($this->_cacheCategory)) {
            require_once("modules/cache/PHPCacheReader.class.php");
            $cr = new PHPCacheReader(GetCachePath(), $this->_cacheCategory);
            $cr->SetRefreshFunction(array($this, "GetRefreshedHTML"));
            $html = $cr->GetValue(
                    $this->_cacheKey, $this->_cacheVersion, $this->_cacheRandFactor
            );
        }
        if ($html == NULL)
            return $this->GetRefreshedHTML();
        else
            return $html;
    }

}
