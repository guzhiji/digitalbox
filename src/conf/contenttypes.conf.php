<?php

return array(
    'article' => array(
        'path' => 'article.php',
        'service' => DB3_SERVICE_ARTICLE,
        'reader' => 'ArticleBox',
        'editor' => 'ArticleEditorBox'
    ),
    'photo' => array(
        'path' => 'photo',
        'service' => DB3_SERVICE_PHOTO,
        'reader' => 'PhotoBox',
        'editor' => 'PhotoEditorBox'
    )
);