<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class ContentBox extends BoxModel {

    /**
     *
     * @param array $args 
     * - mode: "reader"(default) or "editor"
     */
    function __construct($args = array()) {
        parent::__construct(__CLASS__, $args);
        $this->containerTplName = 'box3';
    }

    protected function LoadContent() {
        $mode = $this->GetBoxArgument('mode');
        if (empty($mode))
            $mode = 'reader';
        $id = intval(strGet('id'));
        if (!empty($id)) {
            try {
                LoadIBC1Class('ContentListReader', 'data.catalog');
                $reader = new ContentListReader(DB3_SERVICE_CATALOG);
                $c = $reader->GetContent($id);
                $box = DB3_ContentType_Data($c->Module, $mode);
                if (!empty($box)) {
                    $this->Forward($box, array(
                        'id' => $id,
                        'content' => $c
                    ));
                    return '';
                }
            } catch (Exception $ex) {
                
            }
        }
        $this->Forward('MsgBox', array(
            'msg' => 'The content is not found.',
            'back' => 'back'
        ));
        return '';
    }

    public function After($page) {
        
    }

    public function Before($page) {
        
    }

}