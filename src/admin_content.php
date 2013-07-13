<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require('modules/common.module.php');

require('modules/pages/PortalPage.class.php');

$portalpage = new PortalPage();
$portalpage->Prepare(array(
    'modules' => array(
        'catalog' => array(
            'functions' => array(
                'delete' => array('DeleteCatalog', NULL)
            ),
            'boxes' => array(
                array("CatalogListBox", NULL)
            )
        ),
        'catalog/editor' => array(
            'functions' => array(
                'save' => array('SaveCatalog', NULL)
            ),
            'box' => array('CatalogEditorBox', NULL)
        )
    ),
    'boxes' => array(
        array("MsgBox", array(
                'msg' => '<a href="?module=catalog">catalog</a>'
        ))
    )
));

$portalpage->SetTitle(GetLangData("homepage"));
$portalpage->Show();
