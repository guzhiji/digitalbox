<script language="javascript">
//<![CDATA[
function getSelectedValue(theForm){
	for (var i=0; i<theForm.elements.length; i++){
		var e = theForm.elements[i];
		if (e.checked) return e.value;
	}
	return 0;
}
function addClass(){
	window.location="admin_class.php?function=edit&channel={$channelid}";
}
function editClass(){
	var cid=getSelectedValue(document.admin_class);
	if (cid>0)
		window.location="admin_class.php?function=edit&channel={$channelid}&id="+cid;
	else
		window.alert("Sorry, nothing chosen.");
}
function deleteClass(){
	var f=document.admin_class;
	if (getSelectedValue(f)>0){
		if (window.confirm("Are you sure to delete this class?")){
			f.method="post";
			f.action="admin_class.php?channel={$channelid}&function=delete";
			f.submit();
		}
	}else window.alert("Sorry, nothing chosen.");
}
function goBack(){
	window.location="admin_channel.php";
}
//]]>
</script>
<input type="button" class="button1" onclick="addClass()" value="add" />
<input type="button" class="button1" onclick="editClass()" value="edit" />
<input type="button" class="button1" onclick="deleteClass()" value="delete" />
<input type="button" class="button1" onclick="goBack()" value="back to channel" />
