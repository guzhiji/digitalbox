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
	window.location="admin_content.php?module=content&function=edit";
}
function editChannel(){
	var cid=getSelectedValue(document.admin_channel);
	if (cid>0)
		window.location="admin_content.php?module=content&function=edit&id="+cid;
	else
		window.alert("您未选择对象！");
}
function deleteChannel(){
	var f=document.admin_channel;
	if (getSelectedValue(f)>0){
		if (window.confirm("您真的要删除此频道吗？")){
			f.method="post";
			f.action="admin_content.php?module=content&function=delete";
			f.submit();
		}
	}else window.alert("您未选择对象！");
}
//]]>
</script>
<form name="admin_channel">
    <div style="text-align: center;">
        <table border="0" cellspacing="0" cellpadding="5">
            <tr>
                <th width="20"></th>
                <th width="200" align="left">频道名称</th>
                <th width="100" align="left">频道类型</th>
            </tr>
            {$ListView_ListItems}
        </table>
    </div>
    <div style="text-align: center;">
        <input type="button" class="button1" value="添加频道" onclick="addChannel()" />
        <input type="button" class="button1" value="设置频道" onclick="editChannel()" />
        <input type="button" class="button1" value="删除频道" onclick="deleteChannel()" />
    </div>
</form>
