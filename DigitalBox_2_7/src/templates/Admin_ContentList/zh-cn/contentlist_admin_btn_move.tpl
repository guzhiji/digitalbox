<script language="javascript">
//<![CDATA[
function cancelMove(){
	window.location="admin_content.php?class={$classid}&function=cancelmove";
}
function moveHere(){
	var f=document.admin_content;
	f.method="post";
	f.action="admin_content.php?class={$classid}&type={$type}&function=move";
	f.submit();
}
function goBack(){
	window.location="admin_class.php?channel={$channelid}";
}
//]]>
</script>
<input type="hidden" name="newclass" value="{$classid}" />
<input type="hidden" name="contenttomove" value="{$contentid}" />
<input type="button" class="button1" onclick="moveHere()" value="移至此处" />
<input type="button" class="button1" onclick="cancelMove()" value="取消移动" />
<input type="button" class="button1" onclick="goBack()" value="栏目管理" />
