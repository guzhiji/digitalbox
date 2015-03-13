<script language="javascript">
//<![CDATA[
function ok(theForm){
	for (var i=0; i<theForm.elements.length; i++){
		var e = theForm.elements[i];
		if(e.checked){
			var url="{$UploadPath}/"+e.value;
			window.opener.setAddr(url);
			window.close();
			return;
		}
	}
	window.alert("Sorry, nothing chosen.");
}
//]]>
</script>
