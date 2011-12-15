<form name="" action="admin_setting.php?module=style&function=setdefault" method="post">
    <div style="text-align: left;">{$ListView_Default}</div>
    {$ListView_ListItems}
    <div style="text-align: left;padding-top: 5px;">{$ListView_PagingBar}</div>
    <div>
        <input type="submit" class="button1" value="设为默认" />
        <input type="button" class="button1" value="与目录同步风格" 
               onclick="window.location='admin_setting.php?module=style&function=sync'" />
    </div>
</form>