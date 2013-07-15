<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class ConfirmBox extends BoxModel {

    function __construct($args) {
        parent::__construct(__CLASS__, $args);
        $this->containerTplName = 'box3';
    }

    protected function LoadContent() {
        session_start(); // debug use
        DB3_Operation_ToConfirm($this->GetBoxArgument('operation'));
        $this->SetField('Title', $this->GetBoxArgument('title'));
        return $this->TransformTpl('confirm', array(
                    'msg' => $this->GetBoxArgument('msg'),
                    'yes' => $this->GetBoxArgument('yes'),
                    'no' => $this->GetBoxArgument('no')
                ));
    }

    public function After($page) {
        
    }

    public function Before($page) {
        
    }

}