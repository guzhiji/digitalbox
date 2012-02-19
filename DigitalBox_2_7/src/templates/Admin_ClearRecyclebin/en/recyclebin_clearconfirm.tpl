<form action="admin_recyclebin.php?type={$type}&function=clear" method="post">
    <div style="text-align: center;">Are you sure to clear all content of the type {$type_cn} in the recyclebin?</div>
    <div style="text-align: center;padding-top: 10px;">
        <input type="submit" value="clear" class="button1" /> 
        <input type="button" value="cancel" class="button1" onclick="window.location='admin_recyclebin.php?type={$type}';" />
    </div>
</form>
