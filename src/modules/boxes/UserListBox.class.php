<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class UserListBox extends BoxModel {

    protected $reader;

    function __construct() {
        parent::__construct(__CLASS__);
        $this->containerTplName = 'box3';
    }

    public function LoadContent() {
        $this->SetField('Title', 'Users');
        LoadIBC1Class('UserListReader', 'data.user');
        $reader = new UserListReader(DB3_SERVICE_USER);
        $reader->LoadList();
        $reader->MoveFirst();
        $this->reader = $reader;
        return $this->RenderPHPTpl('admin');
    }

    public function After($page) {
        
    }

    public function Before($page) {
        $page->CheckPassport();
        LoadIBC1Class('UserServiceManager', 'data.user');
        $m = new UserServiceManager(DB3_SERVICE_USER);
        if (!$m->IsInstalled())
            $m->Install();
    }

}