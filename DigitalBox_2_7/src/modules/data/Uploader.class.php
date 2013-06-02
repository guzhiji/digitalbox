<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Uploader {

    var $TotalSize;
    var $FileCount;
    var $error;

    function adaptMaxSize($a, $b, $amax, $comp) {
        if ($comp == '>')
            $c = $a > $amax;
        else
            $c = $a < $amax;
        if ($c) {
            $b = $b / $a * $amax;
            $a = $amax;
        }
        return array($a, $b);
    }

    function createThumb($path, $name) {

        $size = getimagesize($path . $name);
        if (!$size) return FALSE;

        $src = NULL;
        switch ($size['mime']) {
            case 'image/gif':
                $src = imagecreatefromgif($path . $name);
                break;
            
            case 'image/png':
                $src = imagecreatefrompng($path . $name);
                break;
            
            case 'image/jpeg':
            case 'image/pjpeg':
                $src = imagecreatefromjpeg($path . $name);
                break;
        }
        if (empty($src)) return FALSE;

        $w = $size[0];
        $h = $size[1];
        list($w, $h) = $this->adaptMaxSize($w, $h, dbImageThumbMaxW, '>');
        list($h, $w) = $this->adaptMaxSize($h, $w, dbImageThumbMaxH, '>');

        $thumb = @imagecreatetruecolor($w, $h);
        if (!$thumb) return FALSE;
        imagecopyresized($thumb, $src, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);
        if (!is_dir($path . 'thumb')) mkdir($path . 'thumb');
        $newfile = $path . 'thumb/' . $name;

        $suc = FALSE;
        switch ($size['mime']) {
            case 'image/gif':
                $suc = imagegif($thumb, $newfile);
                break;
            
            case 'image/png':
                $suc = imagepng($thumb, $newfile);
                break;
            
            case 'image/jpeg':
            case 'image/pjpeg':
                $suc = imagejpeg($thumb, $newfile);
                break;

        }
        if (empty($suc)) return FALSE;
        return TRUE;
    }

    function getUpload() {
        if (strtolower($_SERVER["REQUEST_METHOD"]) == "post") {

            $this->FileCount = 0;
            $this->TotalSize = 0;

            $UMaxSize = GetSettingValue("upload_maxsize");

            //check server permission
            if (get_cfg_var("file_uploads") != "1") {
                $this->error .= "服务器不支持上传;";
                return;
            }

            //prepare upload path
            $formPath = GetSystemPath() . "/" . dbUploadPath . "/";
            if (!is_dir($formPath))
                @mkdir($formPath);
            if (!is_dir($formPath)) {
                $this->error .= "创建" . dbUploadPath . "目录失败，可能是权限不够导致;";
                return;
            }

            //field counter
            $i = 0;

            //get files
            foreach ($_FILES as $FormField => $NewFile) {

                $i++; //field count

                if ($NewFile["error"])
                    continue;

                //get extension name
                $FileExtName = GetFileExt($NewFile["name"]);

                //get caption
                $FileCaption = "";
                if ($FormField == "UpFile" . $i) {//strict field name: attatch caption
                    $FileCaption = strPost("UpFile" . $i . "Caption");
                }//not strict: default caption only
                if ($FileCaption == "")
                    $FileCaption = $NewFile["name"];

                //validate the file
                if ($NewFile["size"] == 0 || !IsFileTypeAllowed($FileExtName)) {
                    $this->error .= "{$FileCaption}不合法;";
                    continue;
                }

                //count
                $this->FileCount++; //file count
                $this->TotalSize += $NewFile["size"];
                if ($this->TotalSize <= $UMaxSize) {

                    //generate a valid file name
                    $FileName = time() . "." . $FileExtName;
                    while (file_exists($formPath . $FileName)) {
                        $FileName = time() . rand(0, 99) . "." . $FileExtName;
                    }

                    //save file
                    if (@move_uploaded_file($NewFile["tmp_name"], $formPath . $FileName)) {
                        db_query("INSERT INTO upload_info (upload_filename,upload_filecaption) VALUES (\"%s\",\"%s\")", array($FileName, $FileCaption));

                        $this->createThumb($formPath, $FileName);

                    } else {
                        //fail to save file
                        $this->error .= "文件保存失败，可能是写文件权限有限制造成;";
                        $this->FileCount--;
                        $this->TotalSize -= $NewFile["size"];
                    }
                } else {

                    //beyond size limit
                    $this->error .= "总大小超出文件上传限制;";
                    $this->FileCount--;
                    $this->TotalSize -= $NewFile["size"];
                    break;
                }
            }
        } else {
            $this->error .= "未收到数据;";
        }
    }

}
