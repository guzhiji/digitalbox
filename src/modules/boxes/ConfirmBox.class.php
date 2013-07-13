<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class ConfirmBox extends BoxModel {

    function __construct($args) {
        parent::__construct(__CLASS__, $args);
        $this->containerTplName = 'box3';
    }

    protected function LoadContent() {
        $this->SetField('Title', $this->GetBoxArgument('title'));
        return $this->TransformTpl('confirm', array(
                    'msg' => $this->GetBoxArgument('msg'),
                    'url' => $this->GetBoxArgument('url')
                ));
    }

    public function After($page) {
        
    }

    public function Before($page) {
        
    }

}