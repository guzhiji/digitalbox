<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/PopupPage.class.php
  ------------------------------------------------------------------
 */
require_once("modules/view/Page.class.php");

class PopupPage extends Page {

    function __construct() {
        parent::__construct("popuppage");

        $this->_prefix = __CLASS__;

        $this->SetCSSFile("main.css");
    }

}

?>