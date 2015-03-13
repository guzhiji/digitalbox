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
            {$CommentList_ListItems}
        </table>
    </div>
    <div style="text-align: left">{$CommentList_PagingBar}</div>
    <div style="text-align: center">
        <input type="button" class="button1" value="查看" onclick="view_message()" />
        <input type="button" class="button1" value="删除" onclick="delete_message()" />
        <input type="button" class="button1" value="清空" onclick="clear_guestbook()" />
    </div>
</form>