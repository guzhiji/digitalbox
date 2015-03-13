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
                    {$SiteList_ListItems}
                </table></td>
        </tr>
        <tr><td>{$SiteList_PagingBar}</td></tr>
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