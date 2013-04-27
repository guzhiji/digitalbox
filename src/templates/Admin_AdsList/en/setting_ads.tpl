<table>
    <tr><th align="left">Code</th></tr>
    <tr>
        <td align="left">
            <form action="admin_ads.php?function=editcode" method="post">
                {$Setting_Ads} <input type="submit" value="edit" class="button1" />
            </form>
        </td>
    </tr>
    <tr><th align="left">Layout</th></tr>
    <tr>
        <td align="center">
            <form action="admin_ads.php?function=savesettings" method="post">
                <table>
                    <tr>
                        <td>upper-left:{$Setting_Ad1}</td>
                        <td>upper-right:{$Setting_Ad3}</td>
                    </tr>
                    <tr>
                        <td>lower-left:{$Setting_Ad2}</td>
                        <td>lower-right:{$Setting_Ad4}</td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2">
                            <table cellspacing="0" cellpadding="5">
                                <tbody>
                                    <tr>
                                        <td align="center"><input type="submit" value="save" 
                                                                  class="button1" /></td>
                                        <td align="center"><input type="button" onclick="window.location='admin_setting.php'"
                                                                  value="back" class="button1" /></td>
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

