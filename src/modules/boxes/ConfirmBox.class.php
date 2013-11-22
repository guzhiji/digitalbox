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

        // confirmation
        DB3_Operation_ToConfirm($this->GetBoxArgument('operation'));

        // content
        $title = $this->GetBoxArgument('title');
        $msg = $this->GetBoxArgument('msg');

        // translation
        $translation = $this->GetBoxArgument('translation');
        if (!empty($translation)) {
            if ($translation == 'default')
                $translation = NULL;
            $title = GetLangData($title, $translation);
            $msg = GetLangData($msg, $translation);
        }

        $this->SetField('Title', $title);
        return $this->TransformTpl('confirm', array(
                    'msg' => $msg,
                    'yes' => $this->GetBoxArgument('yes'),
                    'no' => $this->GetBoxArgument('no')
                ));
    }

    public function After($page) {
        
    }

    public function Before($page) {
        $page->CheckPassport();
    }

}