<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class AdminMenuBox extends BoxModel {

    function __construct($args = array()) {
        parent::__construct(__CLASS__, $args);
        $this->containerTplName = 'box1';
    }

    protected function LoadContent() {

        $this->SetField('Title', 'Menu');

        require_once GetSysResPath('NaviButtonList.class.php', 'modules/lists');
        $list = new NaviButtonList();
        $list->AddItems(array(
            array(
                'text' => 'Admin Home',
                'url' => 'admin.php'
            ),
            array(
                'text' => 'Catalogs',
                'url' => queryString(array(
                    'module' => 'catalog'
                ))
            ),
            array(
                'text' => 'Users',
                'url' => queryString(array(
                    'module' => 'user'
                ))
            ),
            array(
                'text' => 'Languages',
                'url' => queryString(array(
                    'module' => 'languages'
                ))
            ),
            array(
                'text' => 'Logout',
                'url' => queryString(array(
                    'module' => 'user',
                    'function' => 'logout'
                ))
            )
        ));

        return $list->GetHTML();
    }

    public function After($page) {
        
    }

    public function Before($page) {
        
    }

}