<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

function CheckBasicSettings(&$settings) {
    $error_text = "";

    if ($settings["site_name"] == "" || strlen($settings["site_name"]) > 250)
        $error_text .= "网站名称有误或过长;";

    if (strlen($settings["site_keywords"]) > 250)
        $error_text .= "关键描述过长;";
    else if (strpos($settings["site_keywords"], Chr(34)) > 0)
        $error_text .= "关键描述包含非法字符（如：英文双引号）;";

    if (strlen($settings["master_mail"]) < 5 || strlen($settings["master_mail"]) > 250)
        $error_text .= "站长邮箱有误或过长;";

    if (strlen($settings["site_statement"]) > 250)
        $error_text .= "底部声明过长;";

    if (strlen($settings["icon_URL"]) > 250)
        $error_text .= "Icon地址过长;";

    if (!$settings["logo_hidden"]) {
        if (strlen($settings["logo_URL"]) > 250)
            $error_text .= "LOGO地址过长;";
        else if (strlen($settings["logo_URL"]) < 5)
            $error_text .= "LOGO地址有误;";

        if ($settings["logo_width"] <= 0)
            $error_text .= "LOGO宽度过短;";
        if ($settings["logo_height"] <= 0)
            $error_text .= "LOGO高度过短;";
    }

    if (!$settings["banner_hidden"]) {
        if ($settings["banner_width"] <= 0)
            $error_text .= "横幅宽度过短;";
        if ($settings["banner_height"] <= 0)
            $error_text .= "横幅高度过短;";
    }

    if (!LangExists($settings["default_lang"])) {
        $error_text .= "系统不支持所选语言;";
    }
    return $error_text;
}

function CheckDetailSettings($settings) {
    $error_text = "";

    if ($settings["upload_maxsize"] > 20 * 1024 * 1024)
        $error_text .= "最大上传过大;";
    if (strlen($settings["upload_filetypes"]) > 255)
        $error_text .= "上传允许的文件类型过多;";
    else if ($settings["upload_filetypes"] == "" && $settings["upload_maxsize"] > 0)
        $error_text .= "上传必须要有允许的文件类型;";

    if ($settings["box1_title_maxlen"] < 5
            || $settings["box2_title_maxlen"] < 5
            || $settings["box3_title_maxlen"] < 5)
        $error_text .= "每行标题文字个数不能小于5;";
    else if ($settings["box1_title_maxlen"] > 100
            || $settings["box2_title_maxlen"] > 100
            || $settings["box3_title_maxlen"] > 100)
        $error_text .= "每行标题文字个数不能大于100;";

    if ($settings["general_list_maxlen"] < 3
            || $settings["toplist_maxlen"] < 3
            || $settings["channel_titlelist_maxlen"] < 3
            || $settings["class_titlelist_maxlen"] < 3
            || $settings["site_list_maxlen"] < 3
            || $settings["comment_list_maxlen"] < 3
            || $settings["guestbook_list_maxlen"] < 3)
        $error_text .= "每页标题列表的最大行数不能小于3;";
    else if ($settings["general_list_maxlen"] > 50
            || $settings["toplist_maxlen"] > 50
            || $settings["channel_titlelist_maxlen"] > 50
            || $settings["class_titlelist_maxlen"] > 50
            || $settings["site_list_maxlen"] > 50
            || $settings["comment_list_maxlen"] > 50
            || $settings["guestbook_list_maxlen"] > 50)
        $error_text .= "每页标题列表的最大行数不能大于50;";

    if ($settings["index_grid_maxrow"] < 1
            || $settings["general_grid_maxrow"] < 1)
        $error_text .= "图片列表的行数不能小于1;";
    else if ($settings["index_grid_maxrow"] > 50
            || $settings["general_grid_maxrow"] > 50)
        $error_text .= "图片列表的行数不能大于50;";

    if ($settings["cache_timeout"] < 1)
        $error_text .= "缓存数据生命期不能小于1秒;";
    return $error_text;
}

function SaveSettings($settings) {
    $e = FALSE;
    require_once("modules/cache/PHPCacheEditor.class.php");
    $cm = new PHPCacheEditor("cache", "settings");
    foreach ($settings as $key => $value) {
        $cm->SetValue($key, $value);
        $type = 0;
        if (!is_string($value)) {
            if (is_bool($value)) {
                $type = 2;
                $value = $value ? "true" : "false";
            } else {
                $type = 1;
                if (is_int($value))
                    $value = strval($value);
                else
                    $value = strval(doubleval($value));
            }
        }
        if (!db_query("UPDATE setting_info SET setting_value=\"%s\",setting_type=\"%d\" WHERE setting_name=\"%s\"", array($value, $type, $key))) {
            $e = TRUE;
        }
        global $_connid;
        if (mysql_affected_rows($_connid) < 1) {
            $rs = db_query("SELECT \"true\" FROM setting_info WHERE setting_name=\"%s\"", array($key));
            if ($rs) {
                $list = db_result($rs);
                if (!isset($list[0])) {
                    if (!db_query("INSERT INTO setting_info (setting_name,setting_type,setting_value) VALUES (\"%s\",\"%d\",\"%s\")", array($key, $type, $value))) {
                        $e = TRUE;
                    }
                }
                db_free($rs);
            } else {
                $e = TRUE;
            }
        }
    }
    try {
        $cm->Save();
    } catch (Exception $ex) {
        return FALSE;
    }
    return !$e;
}

function LangExists($lang) {
    $lang = strtolower($lang);
    $langlist = GetSettingValue("languages");
    return isset($langlist[$lang]);
}

function ClearCache() {
    $d = dir("themes");
    while (FALSE != ($id = $d->read())) {
        if (is_numeric($id) && is_dir("cache/" . $id)) {
            $d2 = dir("cache/" . $id);
            while (FALSE != ($lang = $d2->read())) {
                if (substr($lang, 0, 1) == ".")
                    continue;
                $d3 = dir("cache/" . $id . "/" . $lang);
                while (FALSE != ($file = $d3->read())) {
                    if (substr($file, 0, 1) == ".")
                        continue;
                    unlink("cache/" . $id . "/" . $lang . "/" . $file);
                }
                rmdir("cache/" . $id . "/" . $lang);
            }
            rmdir("cache/" . $id);
        }
    }
}