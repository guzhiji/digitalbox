<form method="post" action="?function=login">
    <table border="0" cellpadding="3">
        <tr>
            <td valign="middle">username: </td>
            <td valign="middle" colspan="2"><input class="textinput1" type="text" name="username" /></td>
        </tr>
        <tr>
            <td valign="middle">password: </td>
            <td valign="middle" colspan="2"><input class="textinput1" type="password" name="password" /></td>
        </tr>
        <tr>
            <td valign="middle">check code: </td>
            <td valign="middle"><input class="textinput2" type="text" name="checkcode" /></td>
            <td valign="middle"><a title="refresh" href="#"><img border="0" src="checkcode.php" onclick="refresh_checkcode(this);" /></a></td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <table border="0" width="100%">
                    <tr>
                        <td align="center"><input type="submit" value="login" class="button1" /></td>
                        <td align="center"><input type="button" value="back" class="button1"
                                                  onclick="window.location='index.php';" /></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>
<script language="javascript">
    //<![CDATA[
    function refresh_checkcode(img) {
        if(!window.checkcode_refreshes)
            checkcode_refreshes=0;
        checkcode_refreshes++;
        img.src='checkcode.php?refresh='+checkcode_refreshes;
    }
    //]]>
</script>