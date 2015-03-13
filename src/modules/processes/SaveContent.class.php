<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

abstract class SaveContent extends ProcessModel {

    private $contentModule;

    function __construct($module) {
        $this->contentModule = $module;
    }

    abstract protected function GetInputMeta();

    abstract protected function CreateAttributes($id, $vars);

    abstract protected function ModifyAttributes($id, $vars);

    public function Auth($page) {
        $page->CheckPassport();
        return TRUE;
    }

    public function Process() {

        $up = DB3_Passport();
        $username = $up->GetUID();

        $meta = $this->GetInputMeta();
        $rf = $meta[0]; // required fields
        $vars = readAllParams($meta[1]);
        $output = NULL;

        LoadIBC1Class('ContentItemEditor', 'data.catalog');
        $editor = new ContentItemEditor(DB3_SERVICE_CATALOG);

        try {
            if (empty($vars[$rf['id']])) {
                // ===============create===============
                $editor->Create();

                // set attributes
                $editor->SetUID($username);
                $editor->SetModule($this->contentModule);
                setAllParams($editor, array($rf['name'], $rf['author']), $meta[1], $vars);

                $editor->Save($vars[$rf['pid']]);
                $this->CreateAttributes($editor->GetID(), $vars);

                // success
                $output = $this->OutputBox('MsgBox', array(
                    'msg' => 'succeed',
                    'back' => DB3_URL('admin', 'catalog', '', array(
                        'id' => $vars[$rf['pid']]
                            )
                        ))
                );
            } else {
                // ===============modify===============
                $editor->Open($vars[$rf['id']]);

                // set attributes
                setAllParams($editor, array($rf['name'], $rf['author']), $meta[1], $vars, TRUE);

                $editor->Save();
                $this->ModifyAttributes($editor->GetID(), $vars);

                // success
                $output = $this->OutputBox('MsgBox', array(
                    'msg' => 'succeed',
                    'back' => DB3_URL('admin', 'catalog', '', array(
                        'id' => $vars[$rf['pid']]
                    ))
                        )
                );
            }
        } catch (ServiceException $ex) {
            $output = $this->OutputBox('MsgBox', array(
                'translation' => 'admin',
                'msg' => $ex->getMessage(),
                'back' => 'back'
                    )
            );
        } catch (Exception $ex) {
            // unexpected error
            $output = $this->OutputBox('MsgBox', array(
                'msg' => 'unexpected error: ' . $ex->getMessage(),
                'back' => 'back'
                    )
            );
        }

        return $output;
    }

}