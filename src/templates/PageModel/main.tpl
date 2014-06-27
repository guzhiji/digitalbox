<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <meta name="version" content="{$this->GetVersion()}" />
        <meta name="generator" content="{$this->GetVersion()}" />
        <meta name="keywords" content="{$Keywords}" />
        <meta name="description" content="{$Description}" />
        <title>{$Title} - {$SiteName}</title>
        {$Head}
    </head>
    <body>
        <div class="page">
            <div class="page_hspace">{$TopNaviBar}</div>
            <div class="page_banner">{$Banner}</div>
            <div class="page_hspace2"></div>
            <div class="page_content">
                <div class="page_column_left">{$Left}</div>
                <div class="page_column_right">{$Right}</div>
            </div>
            <div class="page_hspace">{$BottomNaviBar}</div>
            <div class="page_footer">{$Footer}


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
                    <div align="center">Powered by {$this->GetVersion()}</div>

            </div>
            <div class="page_hspace"></div>
        </div>

</body>
</html>
