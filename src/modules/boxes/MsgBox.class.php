<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class MsgBox extends BoxModel {

    function __construct($args) {
        parent::__construct(__CLASS__, $args);
        $this->containerTplName = 'box3';
    }

    protected function LoadContent() {
        $back = $this->GetBoxArgument('back');
        if (!empty($back)) {
            if ($back == 'back')
                $back = 'history.back(1);';
            else
                $back = "location.href='{$back}'";
        }
        $this->SetField('Title', $this->GetBoxArgument('title'));
        return $this->TransformTpl('msg', array(
                    'msg' => $this->GetBoxArgument('msg'),
                    'back' => $back
                ));
    }

    public function After($page) {
        
    }

    public function Before($page) {
        
    }

}