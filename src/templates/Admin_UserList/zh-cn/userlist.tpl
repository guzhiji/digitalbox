<script language="javascript">
    //<![CDATA[
    function isselected(theForm){
    for (var i=0; i<theForm.elements.length; i++){
    var e = theForm.elements[i];
    if (e.checked) return true;
}
return false;
}
function add_user(){
window.location="admin_account.php?module=add";
}
function delete_user(){
if (isselected(document.admin_user)){
if (window.confirm("您真的要删除此管理员吗？")){
document.admin_user.method="post";
document.admin_user.action="admin_account.php?module=delete&function=delete";
document.admin_user.submit();
}
}else window.alert("您未选择对象！");
}

//]]>
</script>
<form name="admin_user" method="post">
    <table border="0">
        <tr>
            <td align="center">
                <table width="230" border="0" cellspacing="0" cellpadding="5">
                    <tr><td colspan="2">站长：{$Master}</td></tr>
                    {$ListItems}
                    <tr><td colspan="2">{$PagingBar}</td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center">
                <table border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <td><input type="button" class="button1" value="添加人员" onclick="add_user()" /></td>
                        <td><input type="button" class="button1" value="删除人员" onclick="delete_user()" /></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>