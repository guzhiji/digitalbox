<form name="admin_vote">
    {$VoteList_Items}
    <div style="padding-top: 30px;">
        <input type="button" onclick="window.location='admin_event.php?module=vote&function={$VoteList_en}'" value="{$VoteList_ch}投票" class="button1" />
        <input type="button" {$VoteList_disable}onclick="window.location='admin_event.php?module=vote&function=set'" value="投票描述" class="button1" />
        <input type="button" {$VoteList_disable}onclick="window.location='admin_event.php?module=vote&function=add'" value="添加项目" class="button1" />
        <input type="button" {$VoteList_disable}onclick="delete_vote()" value="删除项目" class="button1" />
    </div>
</form>