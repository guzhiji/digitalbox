<form method="post" action="?module=delete&function=delete">
    <table border="0" cellpadding="3">
        <tr>
            <td>site master's password: </td>
            <td><input class="textinput1" type="password" name="password" /></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <table border="0" width="100%">
                    <tr>
                        <td align="center"><input type="submit" value="delete" class="button1" /></td>
                        <td align="center"><input type="button" value="cancel" class="button1"
                                                  onclick="javascript:window.location='admin_account.php'" /></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <input type="hidden" name="UID" value="{$Account_TargetUID}" />
</form>
