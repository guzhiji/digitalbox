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
		if (window.confirm("Are you sure to delete the item?")){
			f.method="post";
			f.action="admin_event.php?module=vote&function=delete";
			f.submit();
		}
	}else window.alert("Sorry, nothing chosen.");
}
//]]>
</script>
    {$Items}
    <div style="padding-top: 30px;">
        <input type="button" onclick="window.location='admin_event.php?module=vote&function={$en}'" value="{$ch}" class="button1" />
        <input type="button" {$disable}onclick="window.location='admin_event.php?module=vote&function=set'" value="set description" class="button1" />
        <input type="button" {$disable}onclick="window.location='admin_event.php?module=vote&function=add'" value="add item" class="button1" />
        <input type="button" {$disable}onclick="delete_vote()" value="delete item" class="button1" />
    </div>
</form>
