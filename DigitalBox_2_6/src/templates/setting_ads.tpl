<table>
    <tr><th align="left">代码</th></tr>
    <tr>
        <td align="left">
            <form action="admin_ads.php?function=editcode" method="post">
                {$Setting_Ads} <input type="submit" value="编辑" class="button1" />
            </form>
        </td>
    </tr>
    <tr><th align="left">布局</th></tr>
    <tr>
        <td align="center">
            <form action="admin_ads.php?function=savesettings" method="post">
                <table>
                    <tr>
                        <td>左上方位置：{$Setting_Ad1}</td>
                        <td>右上方位置：{$Setting_Ad3}</td>
                    </tr>
                    <tr>
                        <td>左下方位置：{$Setting_Ad2}</td>
                        <td>右下方位置：{$Setting_Ad4}</td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2">
                            <table cellspacing="0" cellpadding="5">
                                <tbody>
                                    <tr>
                                        <td align="center"><input type="submit" value="保存" 
                                                                  class="button1" /></td>
                                        <td align="center"><input type="button" onclick="window.location='admin_setting.php'"
                                                                  value="返回" class="button1" /></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>

