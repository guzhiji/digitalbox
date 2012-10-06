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
		window.alert("您未选择对象！");
	}
}
function moveContent(){
	var f=document.admin_content;
	if (getSelectedValue(f)>0){
		f.method="post";
		f.action="admin_content.php?class={$classid}&type={$type_en}&function=beginmove";
		f.submit();
	}else window.alert("您未选择对象！");
}
function recycleContent(){
	var f=document.admin_content;
	if (getSelectedValue(f)>0){
		if (window.confirm("您真的要回收此{$type_cn}吗？")){
			f.method="post";
			f.action="admin_content.php?class={$classid}&type={$type_en}&function=recycle";
			f.submit();
		}
	}else window.alert("您未选择对象！");
}
function goBack(){
	window.location="admin_class.php?channel={$channelid}";
}
//]]>
</script>
<input type="button" class="button1" value="添加{$type_cn}" onclick="addContent()" />
<input type="button" class="button1" value="编辑{$type_cn}" onclick="editContent()" />
<input type="button" class="button1" value="移动{$type_cn}" onclick="moveContent()" />
<input type="button" class="button1" value="回收{$type_cn}" onclick="recycleContent()" />
<input type="button" class="button1" value="栏目管理" onclick="goBack()" />
