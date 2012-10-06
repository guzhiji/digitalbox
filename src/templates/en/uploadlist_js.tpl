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
		if(window.confirm("Are you sure to delete the file?")){
			document.admin_upload.method="post";
			document.admin_upload.action="admin_upload.php?function=delete";
			document.admin_upload.submit();
		}
	}else window.alert("Sorry, nothing chosen.");
}
//]]>
</script>
