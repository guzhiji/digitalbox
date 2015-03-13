<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class UserEditorBox extends BoxModel {

    function __construct($args = array()) {
        parent::__construct(__CLASS__, $args);
        $this->containerTplName = 'box3';
    }

    protected function LoadContent() {

        $uid = strGet('uid');
        if (empty($uid)) {
            // not providing user id means creating a new user
            $this->SetField('Title', 'New User');
            return $this->TransformTpl('add', array(
                        'url' => queryString(array(
                            'module' => $this->module,
                            'function' => 'add'
                                )
                        )
                            )
            );
        }
        // otherwise
        // try to find the user
        LoadIBC1Class('UserListReader', 'data.user');
        $reader = new UserListReader(DB3_SERVICE_USER);
        try {
            $c = $reader->GetUser($uid);

            $this->SetField('Title', 'Edit User');
            return $this->TransformTpl('update', array(
                        'url' => queryString(array(
                            'module' => $this->module,
                            'function' => 'update',
                            'uid' => $c->UID
                                )
                        ),
                        'text_name' => $c->UID
                            )
            );
        } catch (Exception $ex) {
            // user not found
            $this->Forward('MsgBox', array(
                'msg' => 'This user is not found.',
                'back' => 'back'
            ));
            return '';
        }
    }

    public function After($page) {
        
    }

    public function Before($page) {
        $page->CheckPassport();
    }

}