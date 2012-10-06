<script language="javascript">
//<![CDATA[
function isselected(theForm){
	for (var i=0; i<theForm.elements.length; i++){
		var e = theForm.elements[i];
		if (e.checked) return true;
	}
	return false;
}
function view_message(){
	if (isselected(document.admin_guestbook)){
		document.admin_guestbook.method="get";
		document.admin_guestbook.target="_blank";
		document.admin_guestbook.action="guestbook.php";
		document.admin_guestbook.submit();
	}else window.alert("您未选择对象！");
}
function delete_message(){
	if (isselected(document.admin_guestbook)){
		if (window.confirm("您真的要删除此条留言吗？")){
			document.admin_guestbook.method="post";
			document.admin_guestbook.target="";
			document.admin_guestbook.action="admin_event.php?module=comment&function=delete";
			document.admin_guestbook.submit();
		}
	}else window.alert("您未选择对象！");
}
function clear_guestbook(){
	if (window.confirm("您真的要清空留言本吗？留言本内容不会被备份！")){
		document.admin_guestbook.method="post";
		document.admin_guestbook.target="";
		document.admin_guestbook.action="admin_event.php?module=comment&function=clear";
		document.admin_guestbook.submit();
	}
}
//]]>
</script>
<form name="admin_guestbook" method="post" action="admin_event.php?module=comment">
    <div style="text-align: center">
        <table border="0" width="100%" cellspacing="0">
            <tr>
                <td></td>
                <th align="center">主题</th>
                <th align="center">作者</th>
                <th align="center">E-mail</th>
                <th align="center">IP地址</th>
                <th align="center">时间</th>
            </tr>
            {$ListItems}
        </table>
    </div>
    <div style="text-align: left">{$PagingBar}</div>
    <div style="text-align: center">
        <input type="button" class="button1" value="查看" onclick="view_message()" />
        <input type="button" class="button1" value="删除" onclick="delete_message()" />
        <input type="button" class="button1" value="清空" onclick="clear_guestbook()" />
    </div>
</form>