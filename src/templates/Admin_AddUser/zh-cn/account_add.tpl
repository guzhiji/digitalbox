<form action="admin_account.php?module=add&function=add" method="post" name="add_user">
<table>
	<tbody>
		<tr>
			<td>站长密码：</td>
			<td><input type="password" name="password" class="textinput1"></td>
		</tr>
		<tr>
			<td>用户名：</td>
			<td><input type="text" name="UID" class="textinput1"></td>
		</tr>
		<tr>
			<td>密 码：</td>
			<td><input type="password" name="PWD" class="textinput1"></td>
		</tr>
		<tr>
			<td>确 认：</td>
			<td><input type="password" name="PWD_check" class="textinput1"></td>
		</tr>
		<tr>
			<td align="center" colspan="2">
			<table border="0" cellspacing="0" cellpadding="5">
				<tbody>
					<tr>
						<td><input type="submit" value="添加" class="button1"></td>
						<td><input type="button"
							onclick="window.location='admin_account.php'" value="返回"
							class="button1"></td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
</form>
