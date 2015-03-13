<?php

LoadIBC1Class('ListModel', 'framework');

class NaviButtonList extends ListModel {

    function __construct() {
        parent::__construct('button', __CLASS__);
    }

    protected function LoadData() {
        
    }

    public function After($page) {
        
    }

    public function Before($page) {
        
    }

    public function AddItems(array $items) {
        foreach ($items as $item) {
            $this->AddItem($item);
        }
    }

    /**
     * add a new button to the list
     * 
     * @param array $vars 
     * <code>
     * array(
     *      'text'=>'text displayed as a link',
     *      'url'=>'url to which the link points',
     *      'tiptext'=>'optional, additional description text to pop up',
     *      'target'=>'optional, which frame loads the page',
     *      'selected'=>'optional, if the button is selected so as to be highlighted'
     * )
     * </code>
     * As to "target", its value can be:
     * - "_blank": load the page in a new window
     * - "parent": load the page in the parent frame
     * - "_self" or "": load the page in the current frame
     * - anything else: load the page in the named frame
     */
    public function AddItem(array $vars) {

        $text = htmlspecialchars($vars['text']);
        $url = str_replace("'", '&#039;', htmlspecialchars($vars['url']));
        $tiptext = isset($vars['tiptext']) ? htmlspecialchars($vars['tiptext']) : '';
        $target = isset($vars['target']) ? $vars['target'] : '';
        $selected = isset($vars['selected']) && $vars['selected'];

        $action = '';
        if (!$selected) {
            switch (strtolower($target)) {
                case '_blank':
                    $action = " onClick=\"window.open('{$url}','','')\"";
                    break;
                case '':
                case '_self':
                    $action = " onClick=\"window.location='{$url}'\"";
                    break;
                case 'parent':
                    $action = " onClick=\"window.parent.location='{$url}'\"";
                    break;
                default:
                    $action = " onClick=\"window.parent.{$target}.location='{$url}'\"";
            }
        }

        $prepared = array(
            'Name' => $text,
            'Action' => $action,
            'TipText' => $tiptext
        );

        if ($selected) {
            if (!isset($this->tpl2))
                $this->tpl2 = GetTemplate('button_selected', __CLASS__);
            if (!empty($this->tpl2))
                $this->_items.=$this->Tpl2HTML($this->tpl2, $prepared);
            else
                parent::AddItem($prepared);
        } else {
            parent::AddItem($prepared);
        }
    }

}