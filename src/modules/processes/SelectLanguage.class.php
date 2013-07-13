<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class SelectLanguage extends ProcessModel {

    public function Process() {
        $languages = GetLanguages();
        $lang = strPost('lang');
        if (isset($languages[$lang])) {
            SetLang($lang);
            return $this->OutputBox('MsgBox', array(
                        'msg' => "Now it's {$languages[$lang]} version.",
                        'title' => GetLangData('changelang'),
                        'back' => htmlspecialchars(strPost('from'))
                    ));
        } else {
            return $this->OutputBox('MsgBox', array(
                        'msg' => "{$languages[$lang]} isn't found.",
                        'title' => GetLangData('changelang'),
                        'back' => '?module=>languages'
                    ));
        }
    }

}