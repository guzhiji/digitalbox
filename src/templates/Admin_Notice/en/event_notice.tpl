<script language="javascript">
//<![CDATA[
function edit_notice(com){
document.admin_notice.action="admin_event.php?module=notice&function=" + com;
document.admin_notice.submit();
}
//]]>
</script>
<form action="admin_event.php?module=notice" method="post"
	name="admin_notice">
<table>
	<tbody>
		<tr>
			<td align="center">
			<table>
				<tbody>
					<tr>
						<td>content：</td>
						<td><textarea name="text" class="textarea1">{$Event_Notice_Text}</textarea></td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
		<tr>
			<td align="center">
			<table cellspacing="0" cellpadding="5">
				<tbody>
					<tr>
						<td><input type="button"
							onclick="edit_notice('{$Event_Notice_Switch_En}')"
							value="{$Event_Notice_Switch_Cn}" class="button1" /></td>
						<td><input type="button" onclick="edit_notice('save')"
							value="save" class="button1" /></td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
</form>
