<form method="post" action="?function=login">
    <table border="0" cellpadding="3">
        <tr>
            <td valign="middle">用户名：</td>
            <td valign="middle" colspan="2"><input class="textinput1" type="text" name="username" /></td>
        </tr>
        <tr>
            <td valign="middle">密&nbsp;&nbsp;码：</td>
            <td valign="middle" colspan="2"><input class="textinput1" type="password" name="password" /></td>
        </tr>
        <tr>
            <td valign="middle">验证码：</td>
            <td valign="middle"><input class="textinput2" type="text" name="checkcode" /></td>
            <td valign="middle"><a title="刷新" href="login.php"><img border="0" src="checkcode.php" /></a></td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <table border="0" width="100%">
                    <tr>
                        <td align="center"><input type="submit" value="登 陆" class="button1" /></td>
                        <td align="center"><input type="button" value="返 回" class="button1"
                                                  onclick="window.location='index.php';" /></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>