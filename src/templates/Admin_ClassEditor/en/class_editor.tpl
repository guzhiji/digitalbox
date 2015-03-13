<form action="admin_class.php?channel={$Content_ChannelID}&function={$Content_Function}" method="post">
<input type="hidden" value="{$Content_ClassID}" name="id" />
<table>
	<tbody>
		<tr>
			<td>channel: </td>
			<td>{$Content_ChannelName}<input type="hidden"
				value="{$Content_ChannelID}" name="channel_id"></td>
		</tr>
		<tr>
			<td>type: </td>
			<td>{$Content_Type}</td>
		</tr>
		<tr>
			<td>class name: </td>
			<td><input type="text" name="class_name" class="textinput1"
				value="{$Content_ClassName}"></td>
		</tr>
		<tr>
			<td align="center" colspan="2">
			<table border="0" cellspacing="0" cellpadding="5">
				<tbody>
					<tr>
						<td><input type="submit" value="save" class="button1"></td>
						<td><input type="button" onclick="history.back(1)" value="back"
							class="button1"></td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
</form>
