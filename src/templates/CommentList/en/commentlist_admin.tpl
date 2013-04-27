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
	}else window.alert("Sorry, nothing chosen.");
}
function delete_message(){
	if (isselected(document.admin_guestbook)){
		if (window.confirm("Are you sure to delete the message?")){
			document.admin_guestbook.method="post";
			document.admin_guestbook.target="";
			document.admin_guestbook.action="admin_event.php?module=comment&function=delete";
			document.admin_guestbook.submit();
		}
	}else window.alert("Sorry, nothing chosen.");
}
function clear_guestbook(){
	if (window.confirm("Are you sure to clear the guestbook? It won't be restored.")){
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
                <th align="center">Subject</th>
                <th align="center">Author</th>
                <th align="center">E-mail</th>
                <th align="center">IP</th>
                <th align="center">Time</th>
            </tr>
            {$ListItems}
        </table>
    </div>
    <div style="text-align: left">{$PagingBar}</div>
    <div style="text-align: center">
        <input type="button" class="button1" value="view" onclick="view_message()" />
        <input type="button" class="button1" value="delete" onclick="delete_message()" />
        <input type="button" class="button1" value="clear" onclick="clear_guestbook()" />
    </div>
</form>
