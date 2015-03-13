<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class HTMLSelect {

    private $name = "";
    private $css = "";
    private $js = "";
    private $value = NULL;
    private $items = array();

    function __construct($name) {
        $this->name = $name;
    }

    public function SetOnChange($js) {
        $this->js = $js;
    }

    public function SetCSSClass($classname) {
        $this->css = $classname;
    }

    public function Select($value) {
        $this->value = $value;
    }

    public function AddItems(array $items) {
        $this->items = $items;
    }

    public function AddItem($name, $value) {
        $this->items[$name] = $value;
    }

    public function GetHTML($optionsonly = FALSE) {
        $html = "";
        if (!$optionsonly) {
            $html = "<select name=\"{$this->name}\"";
            if (!empty($this->css))
                $html.=" class=\"{$this->css}\"";
            if (!empty($this->js))
                $html.=" onchange=\"{$this->js}\"";
            $html.=">\n";
        }
        foreach ($this->items as $value => $name) {
            $html.="<option value=\"{$value}\"";
            if ($value === $this->value)
                $html.=" selected=\"selected\"";
            $html.=">{$name}</option>\n";
        }
        if (!$optionsonly)
            $html.="</select>\n";
        return $html;
    }

}
