<script language="javascript">
//<![CDATA[
function getSelectedValue(theForm){
	for (var i=0; i<theForm.elements.length; i++){
		var e = theForm.elements[i];
		if (e.checked) return e.value;
	}
	return 0;
}
function addChannel(){
	window.location="admin_channel.php?function=edit";
}
function editChannel(){
	var cid=getSelectedValue(document.admin_channel);
	if (cid>0)
		window.location="admin_channel.php?function=edit&id="+cid;
	else
		window.alert("Sorry, nothing chosen.");
}
function deleteChannel(){
	var f=document.admin_channel;
	if (getSelectedValue(f)>0){
		if (window.confirm("Are you sure to delete this channel?")){
			f.method="post";
			f.action="admin_channel.php?function=delete";
			f.submit();
		}
	}else window.alert("Sorry, nothing chosen.");
}
//]]>
</script>
<form name="admin_channel">
    <div style="text-align: center;">
        <table border="0" cellspacing="0" cellpadding="5">
            <tr>
                <th width="20"></th>
                <th width="200" align="left">Name</th>
                <th width="100" align="left">Type</th>
            </tr>
            {$ListItems}
        </table>
    </div>
    <div style="text-align: center;">
        <input type="button" class="button1" value="add" onclick="addChannel()" />
        <input type="button" class="button1" value="edit" onclick="editChannel()" />
        <input type="button" class="button1" value="delete" onclick="deleteChannel()" />
    </div>
</form>
