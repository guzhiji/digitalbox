<?php

return array(
    'modules' => array(
        'catalog' => array(
            'boxes' => array(
                array('CatalogListBox', NULL),
                array('ContentListBox', NULL),
                'Right' => array('PublicMenuBox', NULL)
            )
        ),
        'content/article' => array(
            'boxes' => array(
                array('ArticleBox', NULL),
                'Right' => array('PublicMenuBox', NULL)
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
                'Left' => array('LangBox', NULL),
                'Right' => array('PublicMenuBox', NULL)
            )
        )
    ),
    'boxes' => array(
        array('MsgBox', array(
                'title' => 'Public Home',
                'msg' => <<<EOC
<a href="admin.php">admin home</a>
<br />
<a href="?module=catalog">catalog</a>
<br />
<a href="?module=languages">languages</a>
EOC
            )
        ),
        'Right' => array('PublicMenuBox', NULL)
    )
);