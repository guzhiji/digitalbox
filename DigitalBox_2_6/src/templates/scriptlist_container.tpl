<script language="javascript">
//<![CDATA[
function deleteScript(scriptname){
	if(confirm("确认要删除此脚本吗？您也可以通过暂停启用脚本来隐藏该脚本。")){
		window.location="admin_script.php?function=delete&name="+scriptname;
	}
}
function editScript(scriptname){
    window.location="admin_script.php?function=edit&name="+scriptname;
}
function confirmAdding(form){
    if(confirm("确认要添加脚本吗？")) form.submit();
}
//]]>
</script>
<table>
    <tr><th align="left">添加脚本</th></tr>
    <tr><td>
            <form action="admin_script.php?function=add" method="post">
                脚本名称： <input type="text" name="name" class="textinput2" /> <input type="button" value="添加" onclick="confirmAdding(this.form)" />
            </form>
        </td>
    </tr>
    <tr><th align="left">启用脚本</th></tr>
    <tr><td align="center">
            <form action="admin_script.php?function=set" method="post">
                <div><table>{$ListView_ListItems}</table></div>
                <div>
                    <input type="submit" value="保存" />
                    <input type="button" value="返回" onclick="window.location='admin_setting.php'" />
                </div>
            </form>
        </td>
    </tr>
</table>
