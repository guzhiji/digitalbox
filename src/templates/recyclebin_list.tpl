<form name="admin_recyclebin">
    <div style="text-align: left;padding-bottom: 5px;">
        <input type="button" onclick="change_mode()" value="转到" class="button1" />
        <select class="select2" name="type">{$ListView_Types}</select>
    </div>
    {$ListView_ListItems}
    <div align="left">{$ListView_PagingBar}</div>
    <div style="text-align: center;padding-top: 10px;">
        <input type="button" onclick="restore_recycled()" value="还原{$ListView_Type_cn}" class="button1" />
        <input type="button" onclick="delete_recycled()" value="删除{$ListView_Type_cn}" class="button1" />
        <input type="button" onclick="clear_recycled()" value="清空{$ListView_Type_cn}" class="button1" />
    </div>
</form>