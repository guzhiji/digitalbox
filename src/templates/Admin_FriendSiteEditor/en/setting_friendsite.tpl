<form action="admin_friendsite.php?function={$Setting_FSite_Function}"
	method="post" name="edit_friendsite">
<table>
	<tbody>
		<tr>
			<td>site name:</td>
			<td><input type="text" value="{$Setting_FSite_Name}" name="site_name"
				class="textinput1"></td>
		</tr>
		<tr>
			<td>site URL: </td>
			<td><input type="text" value="{$Setting_FSite_Add}" name="site_add"
				class="textinput1"></td>
		</tr>
		<tr>
			<td>logo URL: </td>
			<td><input type="text" value="{$Setting_FSite_Logo}" name="site_logo"
				class="textinput1"></td>
		</tr>
		<tr>
			<td>description: </td>
			<td><input type="text" value="{$Setting_FSite_Text}" name="site_text"
				class="textinput1"></td>
		</tr>
		<tr>
			<td align="center" colspan="2">
			<table border="0" cellspacing="0" cellpadding="5">
				<tbody>
					<tr>
						<td><input type="submit" value="save" name="submit" class="button1"></td>
						<td><input type="button"
							onclick="history.back(1)" value="back"
							name="back" class="button1"><input type="hidden" name="id" value="{$Setting_FSite_ID}"></td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
</form>
