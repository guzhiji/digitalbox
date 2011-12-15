<form action="admin_content.php?module=content&function={$Content_Function}" method="post" name="channel_form">
<input type="hidden" value="{$Content_ChannelID}" name="id" />
<table>
	<tbody>
		<tr>
			<td>频道名称：</td>
			<td><input type="text" name="channel_name" class="textinput1"
				value="{$Content_ChannelName}" /></td>
		</tr>
		<tr>
			<td>频道类型：</td>
			<td><select name="channel_type" class="select2" onchange="document.getElementById('addr_input').style.display=(this.value==0?'':'none');">{$Content_Types}</select></td>
		</tr>
		<tr{$Content_AddrHidden} id="addr_input">
			<td>频道地址：</td>
			<td><input type="text" value="{$Content_ChannelURL}"
				name="channel_add" class="textinput1" /></td>
		</tr>
		<tr>
			<td align="center" colspan="2">
			<table border="0" cellspacing="0" cellpadding="5">
				<tbody>
					<tr>
						<td><input type="submit" value="确定" class="button1" /></td>
						<td><input type="button"
							onclick="window.location='admin_content.php?module=content'"
							value="返回" class="button1" /></td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
</form>
