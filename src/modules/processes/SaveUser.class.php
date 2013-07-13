<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class SaveUser extends ProcessModel {

    private $mode;

    function __construct($args) {
        $this->mode = $args['mode'];
    }

    public function Process() {

        LoadIBC1Class('UserInfoEditor', 'data.user');
        $e = new UserInfoEditor(SERVICE_USER);

        try {
            switch ($this->mode) {
                case 'add':

                    $e->Create(readParam('post', 'uid'));
                    $e->SetPWD(readParam('post', 'pwd'), readParam('post', 'repeat'));
                    break;

                case 'update':

                    $e->Open(readParam('post', 'uid'), readParam('post', 'pwd'));
                    $pwd = readParam('post', 'newpwd');
                    if (!empty($pwd))
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
                                'msg' => 'unauthorized' . $se->getMessage()
                            ));
                case UserInfoEditor::E_EXISTS:
                    return $this->OutputBox('MsgBox', array(
                                'msg' => 'exists' . $se->getMessage()
                            ));
                case UserInfoEditor::E_INCOMPLETE:
                    return $this->OutputBox('MsgBox', array(
                                'msg' => 'incomplete' . $se->getMessage()
                            ));
                case UserInfoEditor::E_INVALID:
                    return $this->OutputBox('MsgBox', array(
                                'msg' => 'invalid' . $se->getMessage()
                            ));
            }
        } catch (Exception $e) {
            return $this->OutputBox('MsgBox', array(
                        'msg' => 'unknown'
                    ));
        }

        return $this->OutputBox('MsgBox', array(
                    'msg' => 'succeed'
                ));
    }

}