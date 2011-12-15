<form name="admin_user" method="post" action="admin_user.php">
    <table border="0">
        <tr>
            <td align="center">
                <table width="230" border="0" cellspacing="0" cellpadding="5">
                    <tr><td colspan="2">站长：{$ListView_Master}</td></tr>
                    {$ListView_ListItems}
                    <tr><td colspan="2">{$ListView_PagingBar}</td></tr>
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