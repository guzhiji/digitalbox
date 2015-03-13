<form method="post" action="vote.php?command=vote" target="_blank">
    {$VoteList}
    <div style="padding-top: 20px;">
        <input type="submit" class="button1" value="投票" /> 
        <input type="button" class="button1" value="结果" onClick="window.open('vote.php?command=result','','');" />
    </div>
</form>