<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class LoginBox extends BoxModel {

    function __construct() {
        parent::__construct(__CLASS__);
        $this->containerTplName = 'box3';
    }

    protected function LoadContent() {
        $this->SetField('Title', GetLangData('login'));
        return $this->TransformTpl('login', array());
    }

    public function After($page) {
        
    }

    public function Before($page) {
        LoadIBC1Class('UserServiceManager', 'data.user');
        $m = new UserServiceManager(DB3_SERVICE_USER);
        if (!$m->IsInstalled())
            $m->Install();
    }

}
