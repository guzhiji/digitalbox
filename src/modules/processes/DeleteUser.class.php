<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class DeleteUser extends ProcessModel {

    public function Auth($page) {
        $page->CheckPassport();
        return TRUE;
    }

    public function Process() {

        $up = DB3_Passport();
        $useradmin = $up->GetUID();
        $uid = readParam('get', 'uid');

        LoadIBC1Class('UserInfoEditor', 'data.user');
        $e = new UserInfoEditor(DB3_SERVICE_USER);

        try {

            $e->OpenWithPassport($uid, $up);

            $operation = __CLASS__ . "[$uid]";

            if (DB3_Operation_IsConfirmed($operation)) {
                // confirmed

                $e->Delete();

                return $this->OutputBox('MsgBox', array(
                    'msg' => 'succeed',
                    'back' => '?module=user'
                    )
                );
            } else {
                // not confirmed

                return $this->OutputBox('ConfirmBox', array(
                    'translation' => 'admin',
                    'title' => 'are you sure?',
                    'msg' => 'are you sure?',
                    'operation' => $operation,
                    'yes' => queryString_Append(array('confirmed' => 'yes')),
                    'no' => '?module=user'
                    )
                );
            }

        } catch(ServiceException $ex) {
            return $this->OutputBox('MsgBox', array(
                'translation' => 'admin',
                'msg' => $ex->getMessage(),
                'back' => '?module=user'
                )
            );
        } catch(Exception $ex) {
            return $this->OutputBox('MsgBox', array(
                'msg' => 'fail: ' . $ex->getMessage(),
                'back' => '?module=user'
                )
            );
        }

    }
}