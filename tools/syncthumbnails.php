<?php
/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require("modules/common.module.php");
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>同步缩略图 - 附加工具 - DigitalBox <?php echo dbVersion; ?></title>
        <link rel="stylesheet" href="stylesheets/main.css" />
        <link rel="Shortcut Icon" href="DigitalBoxIcon.ico" />
    </head>
    <body>
    <center>
        <table border=0 cellspacing=0 cellpadding=0 width=580>
            <tr>
                <td class="bg_top3" align="left">&nbsp;&nbsp;同步缩略图 - 附加工具</td>
            </tr>
            <tr>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0" width="100%"
                           height="100%">
                        <tr>
                            <td class="bg_border"></td>
                            <td>
                                <table border="0" cellspacing="0" cellpadding="0" width="100%"
                                       height="100%">
                                    <tr>
                                        <td align="center" valign="middle" class="bg_middle"><?php
if (strGet("function") == "sync") {
    if (is_dir(dbUploadPath)) {
        require("modules/data/Uploader.class.php");
        $up = new Uploader();
        $d = dir(dbUploadPath);
        while (FALSE != ($f = $d->read())) {
            if ($f == "." || $f == "..")
                continue;
            if (!is_file(dbUploadPath . "/thumb/" . $f))
                $up->createThumb(dbUploadPath . "/", $f);
        }
        $d->close();
    }
    echo "同步完毕<br /><input type=\"button\" class=\"button1\" value=\"确定\" onclick=\"window.location='toolchecker.php'\"/>";
} else {
    echo "本程序将缩略图与上传图片同步<br />";
    echo "<input type=\"button\" class=\"button1\" value=\"开始同步\" onclick=\"window.location='?function=sync'\"/> ";
    echo "<input type=\"button\" class=\"button1\" value=\"返回\" onclick=\"window.location='toolchecker.php'\"/>";
}
?></td>
                                    </tr>
                                </table>
                            </td>
                            <td class="bg_border"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bg_bottom3"></td>
            </tr>
        </table>
    </center>
</body>
</html>