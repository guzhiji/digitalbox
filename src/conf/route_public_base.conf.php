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
                array('ContentListBox', NULL),
                'Right' => array(
                    array('CatalogNaviBox', NULL)
                )
            )
        ),
        'content' => array(
            'boxes' => array(
                array('ContentBox', NULL),
                'Right' => array(
                    array('CatalogNaviBox', NULL)
                )
            )
        )
    )
);
