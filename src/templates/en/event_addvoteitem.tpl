<form action="admin_event.php?module=vote&function=add" method="post"
	name="admin_vote">
<table>
	<tbody>
		<tr>
			<td align="right">item name: </td>
			<td align="left"><input type="text" name="vote_name"></td>
		</tr>
		<tr>
			<td align="center" colspan="2">
			<table cellspacing="0" cellpadding="5">
				<tbody>
					<tr>
						<td><input type="submit" value="OK" class="button1"></td>
						<td><input type="button"
							onclick="window.location='admin_event.php?module=vote'"
							value="back" class="button1"></td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
</form>
