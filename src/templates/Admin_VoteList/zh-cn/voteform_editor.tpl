<form name="admin_vote">
<script language="javascript">
//<![CDATA[
function isselected(theForm){
    for (var i=0; i<theForm.elements.length; i++){
        var e = theForm.elements[i];if (e.checked) return true;
    }
    return false;
}
function delete_vote(){
	var f=document.admin_vote;
	if (isselected(f)){
		if (window.confirm("您真的要删除此项目吗？")){
			f.method="post";
			f.action="admin_event.php?module=vote&function=delete";
			f.submit();
		}
	}else window.alert("您未选择对象！");
}
//]]>
</script>
    {$Items}
    <div style="padding-top: 30px;">
        <input type="button" onclick="window.location='admin_event.php?module=vote&function={$en}'" value="{$ch}投票" class="button1" />
        <input type="button" {$disable}onclick="window.location='admin_event.php?module=vote&function=set'" value="投票描述" class="button1" />
        <input type="button" {$disable}onclick="window.location='admin_event.php?module=vote&function=add'" value="添加项目" class="button1" />
        <input type="button" {$disable}onclick="delete_vote()" value="删除项目" class="button1" />
    </div>
</form>