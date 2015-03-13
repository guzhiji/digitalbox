<script language="javascript">
//<![CDATA[
function isselected(theForm){
	for (var i=0; i<theForm.elements.length; i++){
		var e = theForm.elements[i];
		if (e.checked) return true; 
	}
	return false;
}
function add_site(){window.location="?module=friendsite&function=add";}
function amend_site(){
	var fsform=document.admin_friendsite;
	var addr="";
	for(var a=0;a<fsform.elements.length;a++){
		var e=fsform.elements[a];
		if(e.type=="radio"&&e.checked){
			addr="admin_friendsite.php?function=save&id="+e.value;
			break;
		}
	}
	if(addr==""){
		window.alert("Sorry, nothing chosen.");
	}else{
		window.location=addr;
	}
}
function delete_site(){
	if (isselected(document.admin_friendsite)){
		if (window.confirm("Are you sure to delete the site?")){
			document.admin_friendsite.method="post";
			document.admin_friendsite.action="admin_friendsite.php?function=delete";
			document.admin_friendsite.submit();
		}
	}else window.alert("Sorry, nothing chosen.");
}
//]]>
</script>
<form name="admin_friendsite" method="post" action="admin_setting.php?module=friendsite">
    <table>
        <tr>
            <td><table>
                    <tr>
                        <td width="20"></td>
                        <th width="100">logo URL</th>
                        <th width="100">site name</th>
                        <th width="100">site URL</th>
                    </tr>
                    {$ListItems}
                </table></td>
        </tr>
        <tr><td>{$PagingBar}</td></tr>
        <tr>
            <td align="center"><table cellpadding="5">
                    <tr>
                        <td><input type="button" class="button1" value="add" onclick="add_site()" /></td>
                        <td><input type="button" class="button1" value="edit" onclick="amend_site()" /></td>
                        <td><input type="button" class="button1" value="delete" onclick="delete_site()" /></td>
                    </tr></table></td>
        </tr>
    </table>
</form>
