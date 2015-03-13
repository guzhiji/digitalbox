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
        array('ArticleBox', NULL),
        'Right' => array('AdminMenuBox', NULL)
    ),
    'functions' => array(
        'delete' => array('DeleteArticle', NULL)
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
                'save' => array('SaveArticle', NULL)
            ),
            'box' => array('ArticleEditorBox', NULL)
        )
    )
);
