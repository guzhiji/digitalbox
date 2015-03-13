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
		if (window.confirm("Are you sure to restore it?")){
			document.admin_recyclebin.method="post";
			document.admin_recyclebin.action="admin_recyclebin.php?type={$Type}&function=restore";
			document.admin_recyclebin.submit();
		}
	}else window.alert("Sorry, nothing chosen.");
}
function delete_recycled(){
	if (isselected(document.admin_recyclebin)){
		if (window.confirm("Are you sure to delete it? It won't be restored.")){
			document.admin_recyclebin.method="post";
			document.admin_recyclebin.action="admin_content.php?module=content&class=0&type={$Type_en}&function=delete";
			document.admin_recyclebin.submit();
		}
	}else window.alert("Sorry, nothing chosen.");
}
function clear_recycled(){
	if (window.confirm("Are you sure to clear all content of the type {$Type_cn}? They won't be restored ever."))
		window.location="admin_recyclebin.php?type={$Type}&function=clear";
}
//]]>
</script>
<form name="admin_recyclebin">
    <div style="text-align: left;padding-bottom: 5px;">
        <input type="button" onclick="change_mode()" value="change type" class="button1" />
        <select class="select2" name="type">{$Types}</select>
    </div>
    {$ListItems}
    <div align="left">{$PagingBar}</div>
    <div style="text-align: center;padding-top: 10px;">
        <input type="button" onclick="restore_recycled()" value="restore {$Type_cn}" class="button1" />
        <input type="button" onclick="delete_recycled()" value="delete {$Type_cn}" class="button1" />
        <input type="button" onclick="clear_recycled()" value="clear {$Type_cn}" class="button1" />
    </div>
</form>
