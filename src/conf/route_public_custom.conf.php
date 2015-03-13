<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

return array(
    'modules' => array(
        'catalog' => array(
            'boxes' => array(
                // array('CatalogListBox', NULL),
                'Right' => array(
                    array('PublicMenuBox', NULL)
                )
            )
        ),
        'content' => array(
            'boxes' => array(
                'Right' => array(
                    array('PublicMenuBox', NULL)
                )
            )
        ),
        'user' => array(
            'functions' => array(
                'login' => array('Login', NULL)
            ),
            'box' => array('LoginBox', NULL)
        ),
        'languages' => array(
            'functions' => array(
                'select' => array('SelectLanguage', NULL)
            ),
            'boxes' => array(
                array('LangBox', NULL),
                'Right' => array('PublicMenuBox', NULL)
            )
        )
    ),
    'boxes' => array(
        array('MsgBox', array(
                'title' => 'Public Home',
                'msg' => <<<EOC
<a href="index.php">public home</a>
<br />
<a href="?module=catalog">catalog</a>
<br />
<a href="?module=languages">languages</a>
EOC
            )
        ),
        'Right' => array(
            array('CatalogNaviBox', NULL),
            array('PublicMenuBox', NULL)
        )
    )
);
