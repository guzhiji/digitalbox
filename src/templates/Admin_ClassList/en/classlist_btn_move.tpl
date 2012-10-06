<script language="javascript">
//<![CDATA[
function getSelectedValue(theForm){
	for (var i=0; i<theForm.elements.length; i++){
		var e = theForm.elements[i];
		if (e.checked) return e.value;
	}
	return 0;
}
function cancelMove(){
	window.location="admin_class.php?function=cancelmove&channel={$channelid}";
}
function moveHere(){
	var f=document.admin_class;
	var cid=getSelectedValue(f);
	if(cid>0){
		f.newclass.value=cid;
		f.method="post";
		f.action="admin_content.php?type={$type}&function=move&class="+cid;
		f.submit();
	}else window.alert("Sorry, nothing chosen.");
}
function goBack(){
	window.location="admin_channel.php";
}
//]]>
</script>
<input type="hidden" name="newclass" />
<input type="hidden" name="contenttomove" value="{$contentid}" />
<input type="button" class="button1" onclick="moveHere()" value="move to class" />
<input type="button" class="button1" onclick="cancelMove()" value="cancel move" />
<input type="button" class="button1" onclick="goBack()" value="back to channel" />
