<form action="admin_account.php?module=changepwd&function=changepwd"
	method="post">
<table>
	<tbody>
		<tr>
			<td>username: </td>
			<td>{$Account_Username}</td>
		</tr>
		<tr>
			<td>original password: </td>
			<td><input type="password" name="old_PWD" class="textinput1"></td>
		</tr>
		<tr>
			<td>new password: </td>
			<td><input type="password" name="new_PWD" class="textinput1"></td>
		</tr>
		<tr>
			<td>confirm: </td>
			<td><input type="password" name="copy_new" class="textinput1"></td>
		</tr>
		<tr>
			<td align="center" colspan="2">
			<table border="0" cellspacing="0" cellpadding="5">
				<tbody>
					<tr>
						<td><input type="submit" value="change" class="button1"></td>
						<td><input type="button"
							onclick="javascript:window.location='admin_account.php'" value="back"
							class="button1"></td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
</form>
