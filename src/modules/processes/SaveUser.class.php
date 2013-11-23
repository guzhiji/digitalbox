<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class SaveUser extends ProcessModel {

    private $_mode;

    function __construct($args) {
        $this->_mode = $args['mode'];
    }

    public function Auth($page) {
        $page->CheckPassport();
        return TRUE;
    }

    public function Process() {

        LoadIBC1Class('UserInfoEditor', 'data.user');
        $e = new UserInfoEditor(DB3_SERVICE_USER);

        try {
            switch ($this->_mode) {
                case 'add':

                    $e->Create(readParam('post', 'uid'));
                    $e->SetPWD(readParam('post', 'pwd'), readParam('post', 'repeat'));
                    break;

                case 'update':

                    $e->Open(readParam('post', 'uid'), readParam('post', 'pwd'));
                    $pwd = readParam('post', 'newpwd');
                    //if (!empty($pwd))
                    $e->SetPWD($pwd, readParam('post', 'repeat'));

                    break;
                default:
                    throw new Exception();
            }

            $e->Save();
        } catch (ServiceException $se) {
            switch ($se->getCode()) {
                case UserInfoEditor::E_UNAUTHORIZED:
                    return $this->OutputBox('MsgBox', array(
                                'translation' => 'admin',
                                'msg' => $se->getMessage(),
                                'back' => 'back'
                                    )
                    );
                case UserInfoEditor::E_EXISTS:
                    return $this->OutputBox('MsgBox', array(
                                'translation' => 'admin',
                                'msg' => $se->getMessage(),
                                'back' => 'back'
                                    )
                    );
                case UserInfoEditor::E_INCOMPLETE:
                    return $this->OutputBox('MsgBox', array(
                                'msg' => 'incomplete' . $se->getMessage(),
                                'back' => 'back'
                                    )
                    );
                case UserInfoEditor::E_INVALID:
                    return $this->OutputBox('MsgBox', array(
                                'msg' => 'invalid' . $se->getMessage(),
                                'back' => 'back'
                                    )
                    );
            }
        } catch (Exception $e) {
            return $this->OutputBox('MsgBox', array(
                        'msg' => 'unknown',
                        'back' => 'back'
                            )
            );
        }

        return $this->OutputBox('MsgBox', array(
                    'msg' => 'succeed',
                    'back' => '?module=user'
                        )
        );
    }

}