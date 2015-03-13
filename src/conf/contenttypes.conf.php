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
    'article' => array(
        'path' => 'article.php',
        'service' => DB3_SERVICE_ARTICLE,
        'reader' => 'ArticleBox',
        'editor' => 'ArticleEditorBox'
    ),
    'photo' => array(
        'path' => 'photo.php',
        'service' => DB3_SERVICE_PHOTO,
        'reader' => 'PhotoBox',
        'editor' => 'PhotoEditorBox'
    )
);