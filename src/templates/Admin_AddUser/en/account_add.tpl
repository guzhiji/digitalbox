<form action="admin_account.php?module=add&function=add" method="post" name="add_user">
<table>
	<tbody>
		<tr>
			<td>site master's password:</td>
			<td><input type="password" name="password" class="textinput1"></td>
		</tr>
		<tr>
			<td>new username:</td>
			<td><input type="text" name="UID" class="textinput1"></td>
		</tr>
		<tr>
			<td>password:</td>
			<td><input type="password" name="PWD" class="textinput1"></td>
		</tr>
		<tr>
			<td>confirm:</td>
			<td><input type="password" name="PWD_check" class="textinput1"></td>
		</tr>
		<tr>
			<td align="center" colspan="2">
			<table border="0" cellspacing="0" cellpadding="5">
				<tbody>
					<tr>
						<td><input type="submit" value="create" class="button1"></td>
						<td><input type="button"
							onclick="window.location='admin_account.php'" value="back"
							class="button1"></td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
</form>
