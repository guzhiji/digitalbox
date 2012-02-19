<form action="admin_friendsite.php?function={$Setting_FSite_Function}"
	method="post" name="edit_friendsite">
<table>
	<tbody>
		<tr>
			<td>网站名称：</td>
			<td><input type="text" value="{$Setting_FSite_Name}" name="site_name"
				class="textinput1"></td>
		</tr>
		<tr>
			<td>网站地址：</td>
			<td><input type="text" value="{$Setting_FSite_Add}" name="site_add"
				class="textinput1"></td>
		</tr>
		<tr>
			<td>logo地址：</td>
			<td><input type="text" value="{$Setting_FSite_Logo}" name="site_logo"
				class="textinput1"></td>
		</tr>
		<tr>
			<td>网站简介：</td>
			<td><input type="text" value="{$Setting_FSite_Text}" name="site_text"
				class="textinput1"></td>
		</tr>
		<tr>
			<td align="center" colspan="2">
			<table border="0" cellspacing="0" cellpadding="5">
				<tbody>
					<tr>
						<td><input type="submit" value="保存" name="submit" class="button1"></td>
						<td><input type="button"
							onclick="history.back(1)" value="返回"
							name="back" class="button1"><input type="hidden" name="id" value="{$Setting_FSite_ID}"></td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
</form>
