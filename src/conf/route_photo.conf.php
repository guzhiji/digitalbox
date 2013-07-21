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
    'boxes' => array(
        array('PhotoBox', NULL),
        'Right' => array('AdminMenuBox', NULL)
    ),
    'modules' => array(
        'list' => array(
            'boxes' => array(
                array('ContentListBox', NULL),
                'Right' => array('AdminMenuBox', NULL)
            )
        ),
        'editor' => array(
            'functions' => array(
                'save' => array('SavePhoto', NULL)
            ),
            'box' => array('PhotoEditorBox', NULL)
        )
    )
);
