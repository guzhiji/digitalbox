<?php

class JQMPage extends PageModel {

    function __construct() {
        parent::__construct(isset($_GET['dialog']) ? 'dialog' : 'page', __CLASS__);
        $this->contentFieldName = 'Left';
    }

    public function After($page) {
        
    }

    public function Before($page) {
        header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.
        $this->SetField('LeftButton', '');
        $this->SetField('RightButton', '');
        $this->SetField('Title', '');
        $this->SetField('Content', '');
        $this->SetField('TopNav', '');
    }

    public function ShowHomeButton($url = NULL) {
        if (empty($url)) {
            $this->SetField('LeftButton', TransformTpl('button_home', array(
                        'name' => '首页',
                        'icon' => 'home',
                        'url' => './'
                            ), 'PageModel'));
        } else {
            $this->SetField('LeftButton', TransformTpl('button_home', array(
                        'name' => '返回',
                        'icon' => 'arrow-l',
                        'url' => $url
                            ), 'PageModel'));
        }
    }

    public function ShowRightButton($data) {
        if (!empty($data)) {
            $this->SetField('RightButton', TransformTpl('button_right', $data, 'PageModel'));
        } else {
            $this->SetField('RightButton', '');
        }
    }

}