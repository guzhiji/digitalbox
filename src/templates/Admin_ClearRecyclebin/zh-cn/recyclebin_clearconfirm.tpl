<form action="admin_recyclebin.php?type={$type}&function=clear" method="post">
    <div style="text-align: center;">确认清空回收站中的{$type_cn}的吗？</div>
    <div style="text-align: center;padding-top: 10px;">
        <input type="submit" value="清空" class="button1" /> 
        <input type="button" value="取消" class="button1" onclick="window.location='admin_recyclebin.php?type={$type}';" />
    </div>
</form>