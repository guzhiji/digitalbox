<form action="admin_event.php?module=vote&function=set" method="post"
	name="admin_vote">
<table>
	<tbody>
		<tr>
			<td align="left">description: </td>
		</tr>
		<tr>
			<td align="center"><input type="text"
				value="{$Event_Vote_Desc}" name="vote_description"></td>
		</tr>
		<tr>
			<td align="center">
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
