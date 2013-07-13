<?php

LoadIBC1Class('ListModel', 'framework');

class AdminItemList extends ListModel {

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
     * 
     * @param array $vars 
     * <code>
     * array(
     *      'text'=>'text displayed as a link',
     *      'url'=>'optional, url to which the link points',
     *      'target'=>'optional, with "url", which frame loads the page',
     *      'tiptext'=>'optional, with "url", additional description text to pop up',
     *      'extra'=> array([optional, more data in the same row]),
     *      'selectable'=>array(//optional
     *          'type'=>'checkbox or radio',
     *          'name'=>'name of the field',
     *          'value'=>'the id'
     *      ),
     *      'buttons'=>array(
     *          'listmodel'=>array(
     *              'name'=>'list model class for buttons',
     *              'path'=>'path to the class file'
     *          ),
     *          'data'=>array(
     *              array([details of a button])
     *          )
     *      )
     * );
     * </code>
     */
    public function AddItem(array $vars) {

        $text = htmlspecialchars($vars['text']);
        if (isset($vars['url'])) {
            $url = htmlspecialchars($vars['url']);
            $url_att = '';
            //  attribute 'title'
            if (isset($vars['tiptext'])) {
                $tiptext = htmlspecialchars($vars['tiptext']);
                $url_att.= " title=\"{$tiptext}\"";
            }
            //  attribute 'target'
            if (isset($vars['target']))
                $url_att.= " target=\"{$vars['target']}\"";
            //wrap with tag 'a'
            $text = $this->TransformTpl('item_link', array(
                'URL' => $url,
                'URL_Att' => $url_att,
                'Text' => $text,
                    )
            );
        }

        $extra = '';
        if (isset($vars['extra'])) {
            foreach ($vars['extra'] as $data) {
                $extra.=$this->TransformTpl('item_extra', array('Data' => $data));
            }
        }

        $selectable = '';
        if (isset($vars['selectable'])) {
            $selectable = $this->TransformTpl('item_selectable', array(
                'Type' => $vars['selectable']['type'] == 'checkbox' ? 'checkbox' : 'radio',
                'Name' => $vars['selectable']['name'],
                'Value' => htmlspecialchars($vars['selectable']['value'])
                    )
            );
        }

        $buttons = '';
        if (isset($vars['buttons'])) {
            require_once $vars['buttons']['listmodel']['path'];
            $l = new $vars['buttons']['listmodel']['name'];
            $l->AddItems($vars['buttons']['data']);
            $buttons = $l->GetHTML();
        }

        parent::AddItem(array(
            'Text' => $text,
            'Selectable' => $selectable,
            'Extra' => $extra,
            'Buttons' => $buttons
        ));
    }

}