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
	window.location="admin_content.php?module=content&function=edit&channel={$channelid}";
}
function editClass(){
	var cid=getSelectedValue(document.admin_class);
	if (cid>0)
		window.location="admin_content.php?module=content&function=edit&channel={$channelid}&id="+cid;
	else
		window.alert("您未选择对象！");
}
function deleteClass(){
	var f=document.admin_class;
	if (getSelectedValue(f)>0){
		if (window.confirm("您真的要删除此栏目吗？")){
			f.method="post";
			f.action="admin_content.php?module=content&channel={$channelid}&function=delete";
			f.submit();
		}
	}else window.alert("您未选择对象！");
}
function goBack(){
	window.location="admin_content.php?module=content";
}
//]]>
</script>
<input type="button" class="button1" onclick="addClass()" value="添加栏目" />
<input type="button" class="button1" onclick="editClass()" value="设置栏目" />
<input type="button" class="button1" onclick="deleteClass()" value="删除栏目" />
<input type="button" class="button1" onclick="goBack()" value="频道管理" />
