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

require("modules/Passport.class.php");
$passport = new Passport();
if (!$passport->check()) {
    //TODO:debug
    exit(0);
}
require("modules/data/Uploader.class.php");
$upl = new Uploader();
$upl->getUpload();
$msg = "";
if ($upl->FileCount > 0) {
    $msg.="接收了{$upl->FileCount}个文件，一共" . GetSizeWithUnit($upl->TotalSize);
} else {
    $msg.="未接收到文件";
}
if ($upl->error != "") {
    $msg = "文件上传失败";
}
db_close();
?>
<html>
    <head>
        <title>Uploader</title>
    </head>
    <body>
        <?php
        echo $msg . " [<a href=\"javascript:history.back(1)\">返回</a>]";
        ?>
    </body>
</html>
