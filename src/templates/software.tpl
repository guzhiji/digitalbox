<table width="520" border="0" cellspacing="0" cellpadding="10">
    <tr>
        <td align="center">
            <table border="0" width="550">
                <tr>
                    <td height="30" width="100" align="center">软件名称：</td>
                    <td height="30" width="175" align="left">{$Software_Name}</td>
                    <td height="30" width="100" align="center">软件作者：</td>
                    <td height="30" width="175" align="left">{$software_Producer}</td>
                </tr>
                <tr>
                    <td height="30" width="100" align="center">软件类型：</td>
                    <td height="30" width="175" align="left">{$Software_Type}</td>
                    <td height="30" width="100" align="center">软件语言：</td>
                    <td height="30" width="175" align="left">{$Software_Language}</td>
                </tr>
                <tr>
                    <td height="30" width="100" align="center">软件大小：</td>
                    <td height="30" width="175" align="left">{$Software_Size}</td>
                    <td height="30" width="100" align="center">运行环境：</td>
                    <td height="30" width="175" align="left">{$Software_Environment}</td>
                </tr>
                <tr>
                    <td height="30" width="100" align="center">软件星级：</td>
                    <td height="30" width="175" align="left">{$Software_Grade}</td>
                    <td height="30" width="100" align="center">更新时间：</td>
                    <td height="30" width="175" align="left">{$Software_Time}</td>
                </tr>
                <tr>
                    <td height="30" width="100" align="center">软件下载：</td>
                    <td height="30" align="left"><a target="_blank" href="software.php?mode=download&id={$Software_ID}">下载</a></td>
                    <td height="30" width="100" align="center">下载次数：</td>
                    <td height="30" width="175" align="left">{$Software_Count}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr><td align="right">{$Software_ControlBar}</td></tr>
</table>