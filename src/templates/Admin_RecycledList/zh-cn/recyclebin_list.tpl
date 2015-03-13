<script language="javascript">
//<![CDATA[
function isselected(theForm){
	for (var i=0; i<theForm.elements.length; i++){
		var e = theForm.elements[i];
		if (e.checked) return true;
	}
	return false;
}
function change_mode(){
	document.admin_recyclebin.method="post";
	document.admin_recyclebin.action="admin_recyclebin.php";
	document.admin_recyclebin.submit();
}
function restore_recycled(){
	if (isselected(document.admin_recyclebin)){
		if (window.confirm("您真的要还原此{$Type_cn}吗？")){
			document.admin_recyclebin.method="post";
			document.admin_recyclebin.action="admin_recyclebin.php?type={$Type}&function=restore";
			document.admin_recyclebin.submit();
		}
	}else window.alert("您未选择对象！");
}
function delete_recycled(){
	if (isselected(document.admin_recyclebin)){
		if (window.confirm("删除后不可恢复，您真的要删除此{$Type_cn}吗？")){
			document.admin_recyclebin.method="post";
			document.admin_recyclebin.action="admin_content.php?module=content&class=0&type={$Type_en}&function=delete";
			document.admin_recyclebin.submit();
		}
	}else window.alert("您未选择对象！");
}
function clear_recycled(){
	if (window.confirm("清空后不可恢复，您真的要清空回收站中的{$Type_cn}吗？"))
		window.location="admin_recyclebin.php?type={$Type}&function=clear";
}
//]]>
</script>
<form name="admin_recyclebin">
    <div style="text-align: left;padding-bottom: 5px;">
        <input type="button" onclick="change_mode()" value="转到" class="button1" />
        <select class="select2" name="type">{$Types}</select>
    </div>
    {$ListItems}
    <div align="left">{$PagingBar}</div>
    <div style="text-align: center;padding-top: 10px;">
        <input type="button" onclick="restore_recycled()" value="还原{$Type_cn}" class="button1" />
        <input type="button" onclick="delete_recycled()" value="删除{$Type_cn}" class="button1" />
        <input type="button" onclick="clear_recycled()" value="清空{$Type_cn}" class="button1" />
    </div>
</form>
