<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Login extends ProcessModel {

    public function Process() {
        $p = DB3_Passport();
        if (strGet('function') == 'login') {
            $username = strPost('uid');
            $password = strPost('pwd');
            try {
                $p->Login($username, $password);
                PageRedirect('admin.php');
            } catch (Exception $ex) {
                return $this->OutputBox('MsgBox', array(
                            'msg' => $ex->getMessage(),
                            'back' => 'back'
                        ));
            }
        } else {
            $p->Logout();
            PageRedirect('index.php?module=user');
        }
    }

}