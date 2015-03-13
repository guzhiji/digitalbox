<script language="javascript">
//<![CDATA[
function isselected(theForm){
	for (var i=0; i<theForm.elements.length; i++){
		var e = theForm.elements[i];
		if(e.checked) return true;
	}
	return false;
}
function delete_upload(){
	if(isselected(document.admin_upload)){
		if(window.confirm("您真的要删除此文件吗？")){
			document.admin_upload.method="post";
			document.admin_upload.action="admin_upload.php?function=delete";
			document.admin_upload.submit();
		}
	}else window.alert("您未选择对象！");
}
//]]>
</script>