<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <meta name="version" content="{$Version}" />
        <meta name="generator" content="{$Version}" />
        <title>{$Title} - 管理中心 - {$SiteName}</title>
        {$Head}
    </head>
    <body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0">
    <center>
        <table height="100%" width="780" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" class="director_bar1">{$TopNaviBar}</td>
            </tr>
            <tr>
                <td class="banner_bar">{$Banner}</td>
            </tr>
            <tr>
                <td class="space_bar1"></td>
            </tr>
            <tr>
                <td valign="top" height="100%" class="background_color">
                    <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="580" height="100%" align="left" valign="top">{$Left}</td>
                            <td width="200" height="100%" align="center" valign="top">{$Right}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="space_bar2"></td>
            </tr>
            <tr>
                <td align="center" class="director_bar2">{$BottomNaviBar}</td>
            </tr>
            <tr>
                <td class="statement_bar">
                    <div align="center">
                        <table border="0" width="80%">
                            <tr>
                                <td align="left">站长：<a href="mailto:{$MasterMail}">{$MasterName}</a></td>
                                <td align="center">网站流量：{$VisitorCount}人次</td>
                                <td align="right">执行时间：{$ElapsedTime}毫秒</td>
                            </tr>
                        </table>
                    </div>
                    <div class="statement_text" align="center">{$Footer}</div>
                    <div align="center">Powered by {$Version}</div>
                </td>
            </tr>
            <tr>
                <td class="space_bar3"></td>
            </tr>

        </table>
    </center>
</body>
</html>
