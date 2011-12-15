<?php

/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  modules/view/Box.class.php
  ------------------------------------------------------------------
 */

class Box {

    private $_type;
    private $_height;
    private $_title;
    private $_content;
    private $_padding;
    private $_align;
    private $_valign;

    function __construct($t) {
        $this->_height = "";
        $this->_title = "";
        $this->_content = "";
        $this->_padding = 0;
        $this->_align = "left";
        $this->_valign = "top";
        $this->SetType($t);
    }

    public function SetType($t) {
        switch ($t) {
            case 2:
                $this->_type = 2;
                break;
            case 3:
                $this->_type = 3;
                break;
            default:
                $this->_type = 1;
                break;
        }
    }

    public function SetHeight($h) {
        $this->_height = $h;
    }

    public function SetTitle($text) {
        $this->_title = $text;
    }

    public function SetContent($html, $align, $valign, $padding) {
        $this->_content = $html;
        $this->_align = $align;
        $this->_valign = $valign;
        $this->_padding = $padding;
    }

    public function GetHTML() {

        $h = "";
        if (is_numeric($this->_height) || substr($this->_height, -1) == "%") {
            $h = " height=\"" . $this->_height . "\"";
        }

        return TransformTpl("box{$this->_type}", array(
                    "Height" => $h,
                    "Title" => $this->_title,
                    "Content" => $this->_content,
                    "Padding" => $this->_padding,
                    "Align" => $this->_align,
                    "VAlign" => $this->_valign
                        ), __CLASS__);
    }

}

?>
