<?php
/*
  ------------------------------------------------------------------
  Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
  admin_editor_uploader.php
  ------------------------------------------------------------------
 */
require("modules/common.module.php");
require("modules/data/database.module.php");
require("modules/Passport.class.php");
$passport = new Passport();
if (!$passport->check()) {
    //TODO:debug
    exit(0);
}
$connid = db_connect();
require("modules/data/Uploader.class.php");
$upl = new Uploader();
$upl->getUpload($connid, 5);
$msg = "";
if ($upl->FileCount > 0) {
    $msg.="接收了{$upl->FileCount}个文件，一共" . GetSizeWithUnit($upl->TotalSize);
} else {
    $msg.="未接收到文件";
}
if ($upl->error != "") {
    $msg = "文件上传失败";
}
db_close($connid);
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
