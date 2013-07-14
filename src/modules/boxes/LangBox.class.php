<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class LangBox extends BoxModel {

    function __construct() {
        parent::__construct(__CLASS__);
        $this->containerTplName = 'box3';
    }

    protected function LoadContent() {
        $this->SetField('Title', GetLangData('changelang'));

        return $this->RenderPHPTpl('select', array(
                    'referrer' => array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER'] : '',
                    'lang' => GetLang(),
                    'languages' => GetLanguages()
                ));
    }

    public function After($page) {
        
    }

    public function Before($page) {
        
    }

}
