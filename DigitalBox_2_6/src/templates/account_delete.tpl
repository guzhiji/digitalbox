<form method="post" action="?module=delete&function=delete">
<table border=0 cellpadding=3>
	<tr>
		<td>密码：</td>
		<td><input class="textinput1" type="password" name="password"></td>
	</tr>
	<tr>
		<td colspan=2 align=center>
		<table border=0 width="100%">
			<tr>
				<td align=center><input type="submit" value="删 除" class="button1"></td>
				<td align=center><input type="button" value="放 弃" class="button1"
					onclick="javascript:window.location='admin_account.php'"></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<input type="hidden" name="uid_deleted" value="{$Account_UID}">
</form>