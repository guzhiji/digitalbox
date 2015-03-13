<?php
/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require("modules/common.module.php");
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>同步上传文件 - 附加工具 - DigitalBox <?php echo DB3_Version; ?></title>
        <link rel="stylesheet" href="stylesheets/main.css" />
        <link rel="Shortcut Icon" href="DigitalBoxIcon.ico" />
    </head>
    <body>
    <center>
        <table border=0 cellspacing=0 cellpadding=0 width=580>
            <tr>
                <td class="bg_top3" align="left">&nbsp;&nbsp;同步上传文件 - 附加工具</td>
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
        $d = dir(dbUploadPath);
        $df = array();
        while (FALSE != ($f = $d->read())) {
            if ($f != "." && $f != "..")
                $df[$f] = TRUE; //file to add
        }
        $rs = db_query("SELECT upload_filename FROM upload_info");
        if ($rs) {
            $list = db_result($rs);
            foreach ($list as $item) {
                if (is_file(dbUploadPath . "/" . $item["upload_filename"]))//file exists & is unnecessary to add
                    $df[$item["upload_filename"]] = FALSE;
                else//not exist & to delete
                    db_query("DELETE FROM upload_info WHERE upload_filename=\"%s\"", array($item["upload_filename"]));
            }
            db_free($rs);
        }
        foreach ($df as $f => $e) {
            if ($e) {//find the file to add
                db_query("INSERT INTO upload_info(upload_filename,upload_filecaption) VALUES (\"%s\",\"%s\")", array($f, $f));
            }
        }
    } else {//no dir, nothing exists
        db_query("DELETE FROM upload_info");
    }
    db_close();
    echo "同步完毕<br /><input type=\"button\" class=\"button1\" value=\"确定\" onclick=\"window.location='toolchecker.php'\"/>";
} else {
    echo "本程序将与本地上传文件目录同步数据库中的记录<br />";
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