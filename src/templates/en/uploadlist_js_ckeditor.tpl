<script language="javascript">
//<![CDATA[
function getUrlParam(paramName){
	var reParam = new RegExp("(?:[\?&]|&amp;)" + paramName + "=([^&]+)", "i");
	var match = window.location.search.match(reParam);
	return (match && match.length > 1) ? match[1] : "";
}
function ok(theForm){
	for (var i=0; i<theForm.elements.length; i++){
		var e = theForm.elements[i];
		if(e.checked){
			var url="{$UploadPath}/"+e.value;
			window.opener.CKEDITOR.tools.callFunction(getUrlParam("CKEditorFuncNum"), url);
			window.close();
			return;
		}
	}
	window.alert("Sorry, nothing chosen.");
}
//]]>
</script>
