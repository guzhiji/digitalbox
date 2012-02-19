<?php
/*
  ------------------------------------------------------------------
  Copyright 2011-2012 DigitalBox Ver 2.7 (by GuZhiji Studio)
  toolchecker.php
  ------------------------------------------------------------------
 */
require("modules/common.module.php");

$ToolFiles = array(
    array("install.php", "系统安装", "帮您完成本系统的安装工作"),
    array("setmaster.php", "更改站长", "帮您解决忘记密码的问题"),
    array("synccache.php", "同步缓存", "帮您解决本地设置缓存被损坏或意外被更改的问题"),
    array("syncuploadedfiles.php", "同步上传文件", "帮您解决本地上传文件与数据库记录不一致的问题")
);

$syspath = GetSystemPath() . "/";

if (strtolower(strGet("command")) == "finish") {

    foreach ($ToolFiles as $tool) {
        if (file_exists($syspath . $tool[0]))
            @unlink($syspath . $tool[0]);
    }
}

$NoTools = TRUE;
for ($i = 0; $i < count($ToolFiles); $i++) {
    if (file_exists($syspath . $ToolFiles[$i][0])) {
        $NoTools = FALSE;
    } else {
        $ToolFiles[$i][0] = "";
    }
}
if ($NoTools)
    PageRedirect("index.php");
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>附加工具 - DigitalBox <?php echo dbVersion; ?></title>
        <link rel="stylesheet" href="stylesheets/main.css" />
        <link rel="Shortcut Icon" href="DigitalBoxIcon.ico" />
    </head>
    <body>
    <center>
        <table border=0 cellspacing=0 cellpadding=0 width=580>
            <tr>
                <td class="bg_top3" align="left">&nbsp;&nbsp;附加工具</td>
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
echo "<table>";
if (strtolower(strGet("command")) == "finish") {
    echo "<tr><td style=\"color: #FF0000;\">可能是权限问题，以下工具删除失败，请手工删除，否则会有安全问题！</td></tr>";
} else {
    echo "<tr><td>如果用好，以下工具必须被删除，否则会有安全问题：</td></tr>";
}
foreach ($ToolFiles as $tool) {
    if ($tool[0] != "") {
        echo "<tr><td><a href=\"{$tool[0]}\">{$tool[1]}</a> - {$tool[2]}</td></tr>";
    }
}
echo "<tr><td align=\"center\"><input type=\"button\" value=\"使用完毕\" class=\"button1\" onclick=\"window.location.href='?command=finish'\"></td></tr>";
echo "</table>";
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
