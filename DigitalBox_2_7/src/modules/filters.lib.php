<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

/**
 * ensure plain text is displayed correctly on an html page
 * 
 * @param string $text
 * @param bool $multiline
 * @return string 
 */
function Text2HTML($text, $multiline) {
    $text = str_replace("&", "&amp;", $text);
    $text = str_replace("<", "&lt;", $text);
    $text = str_replace(">", "&gt;", $text);
    $text = str_replace("  ", "&nbsp;&nbsp;", $text);
    if ($multiline) {
        $text = str_ireplace("\r\n", "<br />", $text);
        $text = str_ireplace("\n", "<br />", $text);
        $text = str_ireplace("\r", "<br />", $text);
    } else {
        $text = SingleLineText($text);
    }
    return $text;
}

/**
 * remove \r and \n
 * @param string $text
 * @return string 
 */
function SingleLineText($text) {
    $text = str_replace("\r\n", " ", $text);
    $text = str_replace("\r", " ", $text);
    $text = str_replace("\n", " ", $text);
    return $text;
}

/**
 * remove all unnecessary spacs in html
 * 
 * @param string $html
 * @return string
 */
function CompressHTML($html) {
    $html = str_replace("\r\n", " ", $html);
    $html = str_replace("\r", " ", $html);
    $html = str_replace("\n", " ", $html);
    $html = str_replace("\t", " ", $html);
    while (strpos($html, "     ") !== FALSE) {
        $html = str_replace("     ", " ", $html);
    }
    while (strpos($html, "  ") !== FALSE) {
        $html = str_replace("  ", " ", $html);
    }
    return $html;
}

/**
 * display plain text correctly in an input text box
 * 
 * replace quotation marks with html entities
 * <input value="[text without quotation marks]" />
 * 
 * @param string $text
 * @return string 
 */
function TextForInputBox($text) {
    $text = str_replace("\n", "", $text);
    $text = str_replace("\r", "", $text);
    $text = str_replace("&", "&amp;", $text);
    $text = str_replace("\"", "&quot;", $text);
    $text = str_replace("<", "&lt;", $text);
    $text = str_replace(">", "&gt;", $text);
    return $text;
}

/**
 * display plain text correctly in a text area
 * 
 * replace angle brackets with html entities
 * <textarea>[text without <,>]</textarea>
 * 
 * @param string $text
 * @return string 
 */
function TextForTextArea($text) {
    $text = str_ireplace("</P>", "", $text);
    $text = str_ireplace("<P>", "\n\n", $text);
    $text = preg_replace('/<br ?\/?>/i', "\n", $text);
    $text = str_replace("&", "&amp;", $text);
    $text = str_replace("<", "&lt;", $text);
    $text = str_replace(">", "&gt;", $text);
    return $text;
}

/**
 * display html correctly in an input text box
 * 
 * replace quotation marks with html entities
 * <input value="[without quotation marks]" />
 * 
 * @param string $html
 * @return string 
 */
function HTMLForInputBox($html) {
    $html = str_replace("\n", "", $html);
    $html = str_replace("\r", "", $html);
    $html = str_replace("&", "&amp;", $html);
    $html = str_replace("\"", "&quot;", $html);
    return $html;
}

/**
 * display html correctly in a text area
 * 
 * replace angle brackets with html entities
 * <input value="[without quotation marks]" />
 * 
 * @param string $html
 * @return string 
 */
function HTMLForTextArea($html) {
    $html = str_replace("&", "&amp;", $html);
    $html = str_replace("<", "&lt;", $html);
    $html = str_replace(">", "&gt;", $html);
    return $html;
}
