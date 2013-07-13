<?php

LoadIBC1Class('ListModel', 'framework');

class NaviLinkList extends ListModel {

    function __construct() {
        parent::__construct('link', __CLASS__);
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
     * assign variables associated with the new item to the item template
     * and append the result to the attribute $_items
     * @param array $vars 
     * <code>
     * array(
     *      'text'=>'text displayed as a link',
     *      'url'=>'url to which the link points',
     *      'tiptext'=>'optional, additional description text to pop up',
     *      'target'=>'optional, which frame loads the page'
     * );
     * </code>
     */
    public function AddItem(array $vars) {
        $extra = '';
        if (isset($vars['tiptext'])) {
            $tiptext = htmlspecialchars($vars['tiptext']);
            $extra.= " title=\"{$tiptext}\"";
        }
        if (isset($vars['target']))
            $extra.= " target=\"{$vars['target']}\"";

        parent::AddItem(array(
            "Text" => htmlspecialchars($vars['text']),
            "URL" => htmlspecialchars($vars['url']),
            "Extra" => $extra
        ));
    }

}