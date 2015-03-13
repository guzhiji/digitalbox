<script language="javascript">
//<![CDATA[
function getSelectedValue(theForm){
	for (var i=0; i<theForm.elements.length; i++){
		var e = theForm.elements[i];
		if (e.checked) return e.value;
	}
	return 0;
}
function addContent(){
	window.location="admin_content.php?class={$classid}&function=edit";
}
function editContent(){
	var cid=getSelectedValue(document.admin_content);
	if(cid>0){
		window.location.href="admin_content.php?class={$classid}&function=edit&id="+cid;
	}else{
		window.alert("Sorry, nothing chosen.");
	}
}
function moveContent(){
	var f=document.admin_content;
	if (getSelectedValue(f)>0){
		f.method="post";
		f.action="admin_content.php?class={$classid}&type={$type_en}&function=beginmove";
		f.submit();
	}else window.alert("Sorry, nothing chosen.");
}
function recycleContent(){
	var f=document.admin_content;
	if (getSelectedValue(f)>0){
		if (window.confirm("Are you sure to recycle it?")){
			f.method="post";
			f.action="admin_content.php?class={$classid}&type={$type_en}&function=recycle";
			f.submit();
		}
	}else window.alert("Sorry, nothing chosen.");
}
function goBack(){
	window.location="admin_class.php?channel={$channelid}";
}
//]]>
</script>
<input type="button" class="button1" value="add {$type_cn}" onclick="addContent()" />
<input type="button" class="button1" value="edit {$type_cn}" onclick="editContent()" />
<input type="button" class="button1" value="move {$type_cn}" onclick="moveContent()" />
<input type="button" class="button1" value="recycle {$type_cn}" onclick="recycleContent()" />
<input type="button" class="button1" value="back to class" onclick="goBack()" />
