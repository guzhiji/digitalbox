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
		window.alert("您未选择对象！");
	}else{
		window.location=addr;
	}
}
function delete_site(){
	if (isselected(document.admin_friendsite)){
		if (window.confirm("您真的要删除此友情链接吗？")){
			document.admin_friendsite.method="post";
			document.admin_friendsite.action="admin_friendsite.php?function=delete";
			document.admin_friendsite.submit();
		}
	}else window.alert("您未选择对象！");
}
//]]>
</script>
<form name="admin_friendsite" method="post" action="admin_setting.php?module=friendsite">
    <table>
        <tr>
            <td><table>
                    <tr>
                        <td width="20"></td>
                        <th width="100">网站LOGO</th>
                        <th width="100">网站名称</th>
                        <th width="100">网站地址</th>
                    </tr>
                    {$ListItems}
                </table></td>
        </tr>
        <tr><td>{$PagingBar}</td></tr>
        <tr>
            <td align="center"><table cellpadding="5">
                    <tr>
                        <td><input type="button" class="button1" value="添加链接" onclick="add_site()" /></td>
                        <td><input type="button" class="button1" value="重设链接" onclick="amend_site()" /></td>
                        <td><input type="button" class="button1" value="删除链接" onclick="delete_site()" /></td>
                    </tr></table></td>
        </tr>
    </table>
</form>