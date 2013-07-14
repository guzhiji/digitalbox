<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class MoveCatalog extends ProcessModel {

    public function Process() {

        $up = new UserPassport(SERVICE_USER); //getPassport()
        if (!$up->IsOnline()) {
            
        } else {
            $this->Output('', array());
        }
    }

}