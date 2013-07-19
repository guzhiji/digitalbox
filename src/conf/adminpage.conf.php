<?php

return array(
    'modules' => array(
        'catalog' => array(
            'functions' => array(
                'delete' => array('DeleteCatalog', NULL)
            ),
            'boxes' => array(
                array('CatalogListBox', NULL),
                array('ContentListBox', NULL),
                'Right' => array('AdminMenuBox', NULL)
            )
        ),
        'catalog/editor' => array(
            'functions' => array(
                'save' => array('SaveCatalog', NULL)
            ),
            'box' => array('CatalogEditorBox', NULL)
        ),
        'content' => array(
            'boxes' => array(
                array('ContentBox', NULL),
                'Right' => array('AdminMenuBox', NULL)
            )
        ),
        'content/editor' => array(
            'functions' => array(
                'save' => array('SaveArticle', NULL)
            ),
//            'box' => array('ContentBox', array('mode' => 'editor'))
            'box' => array('ArticleEditorBox', NULL)
        ),
        'user' => array(
            'functions' => array(
                'delete' => array('DeleteUser', NULL),
                'logout' => array('Login', NULL)
            ),
            'boxes' => array(
                array('UserListBox', NULL),
                'Right' => array('AdminMenuBox', NULL)
            )
        ),
        'user/editor' => array(
            'functions' => array(
                'add' => array('SaveUser', array(
                        'mode' => 'add'
                )),
                'update' => array('SaveUser', array(
                        'mode' => 'update'
                ))
            ),
            'box' => array('UserEditorBox', NULL)
        ),
        'languages' => array(
            'functions' => array(
                'select' => array('SelectLanguage', NULL)
            ),
            'boxes' => array(
                'Left' => array('LangBox', NULL),
                'Right' => array('AdminMenuBox', NULL)
            )
        )
    ),
    'boxes' => array(
        array('MsgBox', array(
                'title' => 'Admin Home',
                'msg' => <<<EOC
<a href="admin.php">admin home</a>
<br />
<a href="?module=catalog">catalog</a>
<br />
<a href="?module=user">user</a>
<br />
<a href="?module=languages">languages</a>
EOC
            )
        ),
        'Right' => array('AdminMenuBox', NULL)
    )
);