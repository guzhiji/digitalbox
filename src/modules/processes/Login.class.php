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
        $up = getPassport();
        if (strGet('logout') != '') {
            $username = strPost('username');
            $password = strPost('password');
            try {
                $up->Login($username, $password);
                PageRedirect('admin.php');
            } catch (Exception $ex) {
                $this->output_box = '';
                $this->output_box_params = array(
                    $ex->getMessage()
                );
            }
        } else {
            $up->Logout();
            PageRedirect('login.php');
        }
    }

}